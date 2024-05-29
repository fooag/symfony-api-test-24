<?php

namespace App\Tests\Controller;

use App\Entity\Kunde;
use App\Entity\VermittlerUser;
use App\Tests\AbstractApiTestBase;
use Doctrine\ORM\EntityManagerInterface;
use Generator;
use Symfony\Component\HttpFoundation\Response;

class KundeTestBase extends AbstractApiTestBase
{
    public function testNeedsAuthenticationFor_Foo_Kunden(): void
    {
        $client = static::createClient();
        $client->request('GET', '/foo/kunden');

        self::assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testKundenAreOnlyRetrievedForAuthenticatedVermittlerUser(): void
    {
        $client = $this->createClientWithCredentials(
            username: 'vermittler_klaus_warner@email.com',
            password: 'hackme',
        );
        $client->request('GET', '/foo/kunden');

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }

    /**
     * @dataProvider activeVermittlerEmailDataProvider
     */
    public function testOnlyActiveKundenOwnedByVermittlerAreRetrieved(string $email): void
    {
        $user = static::getContainer()
            ->get(EntityManagerInterface::class)
            ->getRepository(VermittlerUser::class)
            ->findOneBy([
                'email' => $email,
            ])
        ;

        $kunden = static::getContainer()
            ->get(EntityManagerInterface::class)
            ->getRepository(Kunde::class)
            ->findBy([
                'vermittler' => $user->vermittler,
                'geloescht' => 0,
            ])
        ;

        $client = $this->createClientWithCredentials(
            username: $email,
            password: 'hackme',
        );
        $response = $client->request('GET', '/foo/kunden');

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        self::assertMatchesResourceCollectionJsonSchema(Kunde::class);

        $json = json_decode($response->getContent(), true);
        self::assertEquals(
            expected: count($kunden),
            actual: $json['hydra:totalItems']
        );

        $kundenIds = array_column($kunden, 'id');
        foreach ($json['hydra:member'] as $hydraMember) {
            self::assertContains($hydraMember['id'], $kundenIds);
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

    public function testVermittlerIsNotPartOfKundenResponse(): void
    {
        $client = $this->createClientWithCredentials(
            username: 'vermittler_klaus_warner@email.com',
            password: 'hackme',
        );
        $response = $client->request('GET', '/foo/kunden');

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        self::assertMatchesResourceCollectionJsonSchema(Kunde::class);

        $json = json_decode($response->getContent(), true);
        foreach ($json['hydra:member'] as $hydraMember) {
            self::assertArrayNotHasKey('vermittler', $hydraMember);
        }
    }
}
