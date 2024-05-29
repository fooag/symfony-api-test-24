<?php

namespace App\Tests\Controller;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Kunde;
use App\Entity\VermittlerUser;
use Doctrine\ORM\EntityManagerInterface;
use Generator;
use Symfony\Component\HttpFoundation\Response;

class KundeTest extends ApiTestCase
{
    public function testNeedsAuthenticationFor_Foo_Kunden(): void
    {
        $client = static::createClient();
        $client->request('GET', '/foo/kunden');

        self::assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testKundenAreOnlyRetrievedForAuthenticatedVermittlerUser(): void
    {
        $user = static::getContainer()
            ->get(EntityManagerInterface::class)
            ->getRepository(VermittlerUser::class)
            ->findOneBy([
                'email' => 'vermittler_klaus_warner@email.com',
            ])
        ;

        $client = static::createClient();
        $client->loginUser($user);
        $client->request('GET', '/foo/kunden');

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }

    /**
     * @dataProvider vermittlerEmailDataProvider
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

        $client = static::createClient();
        $client->loginUser($user);
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
            self::assertTrue(in_array($hydraMember['id'], $kundenIds));
        }
    }

    /**
     * @dataProvider
     * @return Generator<int ,string>
     */
    public function vermittlerEmailDataProvider(): Generator
    {
        yield 'vermittler_klaus_warner@email.com' => ['vermittler_klaus_warner@email.com'];
        yield 'vermittler_svenja_schuster@email.com' => ['vermittler_svenja_schuster@email.com'];
        yield 'vermittler_vincent_vincent@email.com' => ['vermittler_vincent_vincent@email.com'];
    }
}
