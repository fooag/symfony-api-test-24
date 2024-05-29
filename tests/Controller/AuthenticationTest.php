<?php

namespace App\Tests\Controller;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class AuthenticationTest extends ApiTestCase
{
    public function testApiDocumentationIsPubliclyAccessible(): void
    {
        $client = static::createClient();
        $client->request('POST', '/foo');
        self::assertResponseIsSuccessful();
    }
}
