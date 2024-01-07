<?php

namespace Api\Front\Account;

use App\Front\Account\ApiResource\AccountResource;
use App\Tests\Api\AbstractTest;
use App\Tests\Factory\AccountFactory;
use App\Tests\Factory\UserFactory;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;
use function Zenstruck\Foundry\faker;

class AccountTest extends AbstractTest
{
    use ResetDatabase, Factories;

    public function testGetCollection(): void
    {
        $user = UserFactory::createOne(['email' => 'admin@example.com', 'password' => 'admin']);
        AccountFactory::createOne(['user' => $user]);

        static::createClientWithCredentials()->request('GET', '/api/front/accounts');

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertMatchesResourceCollectionJsonSchema(AccountResource::class);
    }

    public function testCreateAccount(): void
    {
        UserFactory::createOne(['email' => 'admin@example.com', 'password' => 'admin']);

        $response = static::createClientWithCredentials()->request('POST', '/api/front/accounts', [
            'json' => [
                "login" => faker()->unique()->text(),
                "password" => faker()->password,
                "tradeServer" => faker()->text,
                "mtVersion" => 3,
                "balance" => 0
            ],
            'headers' => [
                'content-type' => 'application/ld+json'
            ],
        ]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertMatchesRegularExpression('~^/api/front/accounts/\d+$~', $response->toArray()['@id']);
        $this->assertMatchesResourceItemJsonSchema(AccountResource::class);
    }
}
