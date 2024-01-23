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

        static::createClientWithCredentials()->request('GET', '/api/admin/accounts/' . $account->getId() . '/logs');

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertMatchesResourceCollectionJsonSchema(AccountLogResource::class);
    }
}
