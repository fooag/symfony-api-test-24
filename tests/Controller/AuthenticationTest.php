<?php

namespace App\Tests\Controller;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Symfony\Component\HttpFoundation\Response;

class AuthenticationTest extends ApiTestCase
{
    public function testApiDocumentationIsPubliclyAccessible(): void
    {
        $client = static::createClient();
        $client->request('POST', '/foo');
        self::assertResponseIsSuccessful();
    }

    public function testDeletedVermittlerUserCantLogin(): void
    {
        static::createClient()->request(
            method: 'POST',
            url: '/login',
            options: [
                'json' => [
                    'username' => 'vermittler_vincent_vincent@email.com',
                    'password' => 'hackme',
                ],
            ]
        );

        self::assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
        self::assertJson('{"code":401,"message":"Der Account existiert nicht mehr."}');
    }
}
