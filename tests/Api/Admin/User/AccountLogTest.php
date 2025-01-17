<?php

namespace Api\Admin\User;

use App\Admin\Account\ApiResource\AccountLogResource;
use App\Admin\User\ApiResource\UserResource;
use App\Tests\Api\AbstractTest;
use App\Tests\Factory\AccountFactory;
use App\Tests\Factory\AccountLogFactory;
use App\Tests\Factory\UserFactory;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class AccountLogTest extends AbstractTest
{
    use ResetDatabase, Factories;

    public function testGetCollection(): void
    {
        UserFactory::createOne(['email' => 'admin@example.com', 'password' => 'admin']);

        $account = AccountFactory::createOne();
        AccountLogFactory::createMany(50, ['owner' => $account]);

        $response = static::createClientWithCredentials()->request('GET', '/api/admin/accounts/' . $account->getId() . '/account-logs');

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        // Asserts that the returned JSON is a superset of this one
        $this->assertJsonContains([
            '@context' => '/api/contexts/AccountLog',
            '@id' => '/api/admin/accounts/1/account-logs',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 50,
            'hydra:view' => [
                '@id' => '/api/admin/accounts/1/account-logs?page=1',
                '@type' => 'hydra:PartialCollectionView',
                'hydra:first' => '/api/admin/accounts/1/account-logs?page=1',
                'hydra:last' => '/api/admin/accounts/1/account-logs?page=5',
                'hydra:next' => '/api/admin/accounts/1/account-logs?page=2',
            ],
        ]);

        // Because test fixtures are automatically loaded between each test, you can assert on them
        $this->assertCount(10, $response->toArray()['hydra:member']);
        $this->assertMatchesResourceCollectionJsonSchema(AccountLogResource::class);
    }

    public function testGetSingleItem(): void
    {
        UserFactory::createOne(['email' => 'admin@example.com', 'password' => 'admin']);

        $account = AccountFactory::createOne();
        $accountLog = AccountLogFactory::createOne(['owner' => $account, 'type' => 'unknown_error']);

        $accountLogId = $accountLog->getId();
        static::createClientWithCredentials()->request('GET', '/api/admin/account-logs/' . $accountLogId);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
            "@context" => "/api/contexts/AccountLog",
            "@id" => "/api/admin/account-logs/" . $accountLogId,
            "@type" => "AccountLog",
            "id" => $accountLogId,
            "type" => "unknown_error",
            "data" => [],
            'owner' => '/api/admin/accounts/' . $account->getId(),
        ]);

        $this->assertMatchesResourceItemJsonSchema(AccountLogResource::class);
    }

    public function testCreateSystemLog(): void
    {
        UserFactory::createOne(['email' => 'admin@example.com', 'password' => 'admin']);

        $account = AccountFactory::createOne();

        $response = static::createClientWithCredentials()->request('POST', '/api/admin/accounts/' . $account->getId() . '/account-logs', [
            'json' => [
                'type' => 'unknown_error',
                'data' => [],
            ],
            'headers' => [
                'content-type' => 'application/ld+json'
            ],
        ]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertMatchesResourceItemJsonSchema(AccountLogResource::class);
    }
}
