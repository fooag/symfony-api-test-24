<?php

declare(strict_types=1);

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Client;

class AbstractApiTestBase extends ApiTestCase
{
    /** @var array<string, string> */
    private array $tokens = [];

    public function setUp(): void
    {
        self::bootKernel();
    }

    protected function createClientWithCredentials(
        string $username,
        string $password
    ): Client {
        $token = $this->getToken($username, $password);
        return $this->createClientWithToken($token);
    }

    protected function createClientWithToken(string $token): Client
    {
        return static::createClient([], ['headers' => ['authorization' => 'Bearer '.$token]]);
    }

    protected function getToken(string $username, string $password): string
    {
        if (array_key_exists($username, $this->tokens)) {
            return $this->tokens[$username];
        }

        $response = static::createClient()->request(
            method: 'POST',
            url: '/login',
            options: [
                'json' => [
                    'username' => $username,
                    'password' => $password,
                ],
            ]
        );

        self::assertResponseIsSuccessful();
        $data = $response->toArray();
        $this->tokens[$username] = $data['token'];

        return $data['token'];
    }
}