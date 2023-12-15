<?php

namespace App\Tests\Api\User;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Front\User\ApiResource\UserResource;
use App\Tests\Api\AbstractTest;
use App\Tests\Factory\UserFactory;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class UserTest extends AbstractTest
{
    use ResetDatabase, Factories;

    public function testGetCollection(): void
    {
        UserFactory::createMany(100);

        $response = static::createClient()->request('GET', '/api/users');

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertMatchesResourceCollectionJsonSchema(UserResource::class);
    }

    public function testCreateUser(): void
    {
        $response = static::createClient()->request('POST', '/api/users', ['json' => [
            'email' => 'new@user.pl',
            'password' => 'zdf!23$g%d',
            'roles' => [],
        ]]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertMatchesRegularExpression('~^/api/users/\d+$~', $response->toArray()['@id']);
        $this->assertMatchesResourceItemJsonSchema(UserResource::class);
    }

    public function testCreateInvalidUser(): void
    {
        static::createClient()->request('POST', '/books', ['json' => [
            'email' => 'invalid',
        ]]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }
}
