<?php

namespace App\Tests\Controller;

use App\Entity\Adresse;
use App\Entity\Kunde;
use App\Entity\KundeAdresse;
use App\Entity\VermittlerUser;
use App\Tests\AbstractApiTestBase;
use Doctrine\ORM\EntityManagerInterface;
use Generator;
use Symfony\Component\HttpFoundation\Response;

class AdresseTest extends AbstractApiTestBase
{
    public function testNeedsAuthentication(): void
    {
        $client = static::createClient();
        $client->request('GET', '/foo/adressen');

        self::assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testAdressenAreOnlyRetrievedForAuthenticatedVermittlerUser(): void
    {
        $client = $this->createClientWithCredentials(
            username: 'vermittler_klaus_warner@email.com',
            password: 'hackme',
        );
        $response = $client->request('GET', '/foo/adressen');

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }

    /**
     * @dataProvider activeVermittlerEmailDataProvider
     */
    public function testOnlyActiveAdressenOwnedByVermittlerAreRetrieved(string $email): void
    {
        $user = static::getContainer()
            ->get(EntityManagerInterface::class)
            ->getRepository(VermittlerUser::class)
            ->findOneBy([
                'email' => $email,
            ])
        ;

        $adresseFqns = Adresse::class;
        $kundeAdresseFqns = KundeAdresse::class;
        $kundeFqns = Kunde::class;
        $adressen = static::getContainer()
            ->get(EntityManagerInterface::class)
            ->createQuery(<<<DQL
            SELECT adresse
            FROM $adresseFqns adresse
            JOIN $kundeAdresseFqns kundeAdresse WITH kundeAdresse.adresse = adresse.id AND kundeAdresse.geloescht = :false
            JOIN $kundeFqns kunde WITH kundeAdresse.kunde = kunde.id AND kunde.geloescht = :zero
            WHERE kunde.vermittler = :vermittler
        DQL)
            ->setParameter('false', false)
            ->setParameter('zero', 0)
            ->setParameter('vermittler', $user->vermittler)
            ->getResult();

        $client = $this->createClientWithCredentials(
            username: $email,
            password: 'hackme',
        );
        $response = $client->request('GET', '/foo/adressen');

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        self::assertMatchesResourceCollectionJsonSchema(Kunde::class);

        $json = json_decode($response->getContent(), true);
        self::assertEquals(
            expected: count($adressen),
            actual: $json['hydra:totalItems']
        );

        $adressenIds = array_column($adressen, 'id');
        foreach ($json['hydra:member'] as $hydraMember) {
            self::assertContains($hydraMember['id'], $adressenIds);
        }
    }

    /**
     * @dataProvider
     * @return Generator<int ,string>
     */
    public function activeVermittlerEmailDataProvider(): Generator
    {
        yield 'vermittler_klaus_warner@email.com' => ['vermittler_klaus_warner@email.com'];
        yield 'vermittler_svenja_schuster@email.com' => ['vermittler_svenja_schuster@email.com'];
    }
}
