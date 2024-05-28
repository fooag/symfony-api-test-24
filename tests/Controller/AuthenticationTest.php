<?php

namespace App\Tests\Controller;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Symfony\Component\HttpFoundation\Response;

class AuthenticationTest extends ApiTestCase
{
    public function testNeedsAuthenticationFor_Foo_Kunden(): void
    {
        $client = static::createClient();
        $client->request('GET', '/foo/kunden');

        self::assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testApiDocumentationIsPubliclyAccessible(): void
    {
        $client = static::createClient();
        $client->request('POST', '/foo');
        self::assertResponseIsSuccessful();
    }
}
