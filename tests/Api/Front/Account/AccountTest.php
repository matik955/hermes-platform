<?php

namespace Api\Front\Account;

use App\Front\Account\ApiResource\AccountResource;
use App\Tests\Api\AbstractTest;
use App\Tests\Factory\AccountFactory;
use App\Tests\Factory\UserFactory;
use Symfony\Component\HttpFoundation\Response;
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

        $user2 = UserFactory::createOne(['email' => 'admin2@example.com', 'password' => 'admin']);
        AccountFactory::createOne(['user' => $user2]);

        $response = static::createClientWithCredentials()->request('GET', '/api/front/accounts');

        $this->assertResponseIsSuccessful();
        $this->assertCount(1, $response->toArray()['hydra:member']);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertMatchesResourceCollectionJsonSchema(AccountResource::class);
    }

    public function testCreateAccount(): void
    {
        UserFactory::createOne(['email' => 'admin@example.com', 'password' => 'admin']);

        $response = static::createClientWithCredentials()->request('POST', '/api/front/accounts', [
            'json' => [
                "login" => faker()->unique()->text(16),
                "password" => faker()->password(),
                "tradeServer" => faker()->text(),
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

    public function testUpdateAccount(): void
    {
        $user = UserFactory::createOne(['email' => 'admin@example.com', 'password' => 'admin']);
        $account = AccountFactory::createOne(['user' => $user]);

        $response = static::createClientWithCredentials()->request('PUT', '/api/front/accounts/' . $account->getId(), [
            'json' => [
                "login" => faker()->unique()->text(16),
                "password" => faker()->password(),
                "tradeServer" => faker()->text(),
                "mtVersion" => 3,
                "balance" => 0
            ],
            'headers' => [
                'content-type' => 'application/ld+json'
            ],
        ]);

        dump(json_decode($response->getContent(false), true));
        exit();

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertMatchesRegularExpression('~^/api/front/accounts/\d+$~', $response->toArray()['@id']);
        $this->assertMatchesResourceItemJsonSchema(AccountResource::class);
    }

    public function testDeleteAccount(): void
    {
        $user = UserFactory::createOne(['email' => 'admin@example.com', 'password' => 'admin']);
        $account = AccountFactory::createOne(['user' => $user]);

        $response = static::createClientWithCredentials()->request('DELETE', '/api/front/accounts/' . $account->getId(), [
            'headers' => [
                'content-type' => 'application/ld+json'
            ],
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }
}
