<?php

namespace Api\Admin\User;

use App\Admin\User\ApiResource\UserResource;
use App\Tests\Api\AbstractTest;
use App\Tests\Factory\UserFactory;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class UserTest extends AbstractTest
{
    use ResetDatabase, Factories;

    public function testGetCollection(): void
    {
        UserFactory::createOne(['email' => 'admin@example.com', 'password' => 'admin']);
        UserFactory::createMany(100);

        static::createClientWithCredentials()->request('GET', '/api/admin/users');

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertMatchesResourceCollectionJsonSchema(UserResource::class);
    }

    public function testCreateUser(): void
    {
        $response = static::createClient()->request('POST', '/api/admin/users', [
            'json' => [
                'email' => 'new@user.pl',
                'password' => 'zdf!23$g%d',
                'roles' => [],
            ],
            'headers' => [
                'content-type' => 'application/ld+json'
            ],
        ]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertMatchesRegularExpression('~^/api/admin/users/\d+$~', $response->toArray()['@id']);
        $this->assertMatchesResourceItemJsonSchema(UserResource::class);
    }

    public function testCreateInvalidUser(): void
    {
        static::createClient()->request('POST', '/api/admin/users', [
            'json' => [
                'email' => 'invalid',
                'password' => 'rt&xc76cxB',
                'roles' => [],
            ],
            'headers' => [
                'content-type' => 'application/ld+json'
            ],
        ]);

        $this->assertResponseStatusCodeSame(422);
    }
}
