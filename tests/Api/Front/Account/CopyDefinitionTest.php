<?php

namespace App\Tests\Api\Front\Account;

use App\Front\Account\ApiResource\CopyDefinitionResource;
use App\Tests\Api\AbstractTest;
use App\Tests\Factory\AccountFactory;
use App\Tests\Factory\CopyDefinitionFactory;
use App\Tests\Factory\UserFactory;
use Symfony\Component\HttpFoundation\Response;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;
use function Zenstruck\Foundry\faker;

class CopyDefinitionTest extends AbstractTest
{
    use ResetDatabase, Factories;

    public function testGetCollection(): void
    {
        $user = UserFactory::createOne(['email' => 'admin@example.com', 'password' => 'admin']);
        $sourceAccount = AccountFactory::createOne(['user' => $user]);
        $targetAccount = AccountFactory::createOne(['user' => $user]);
        CopyDefinitionFactory::createOne(['sourceAccount' => $sourceAccount, 'targetAccount' => $targetAccount]);

        $response = static::createClientWithCredentials()->request('GET', '/api/front/accounts/' . $sourceAccount->getId() . '/copy-definitions');

        $this->assertResponseIsSuccessful();
        $this->assertCount(1, $response->toArray()['hydra:member']);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertMatchesResourceCollectionJsonSchema(CopyDefinitionResource::class);
    }

    public function testCreateAccount(): void
    {
        $user = UserFactory::createOne(['email' => 'admin@example.com', 'password' => 'admin']);
        $sourceAccount = AccountFactory::createOne(['user' => $user]);
        $targetAccount = AccountFactory::createOne(['user' => $user]);

        $response = static::createClientWithCredentials()->request('POST', '/api/front/accounts/' . $sourceAccount->getId() . '/copy-definitions', [
            'json' => [
                "sourceAccount" => "/api/front/accounts/" . $sourceAccount->getId(),
                "targetAccount" => "/api/front/accounts/" . $targetAccount->getId()
            ],
            'headers' => [
                'content-type' => 'application/ld+json'
            ],
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertMatchesResourceItemJsonSchema(CopyDefinitionResource::class);
    }

    public function testDeleteCopyDefinition(): void
    {
        $user = UserFactory::createOne(['email' => 'admin@example.com', 'password' => 'admin']);
        $sourceAccount = AccountFactory::createOne(['user' => $user]);
        $targetAccount = AccountFactory::createOne(['user' => $user]);
        $copyDefinition = CopyDefinitionFactory::createOne(['sourceAccount' => $sourceAccount, 'targetAccount' => $targetAccount]);

        $response = static::createClientWithCredentials()->request('DELETE', '/api/front/copy-definitions/' . $copyDefinition->getId(), [
            'headers' => [
                'content-type' => 'application/ld+json'
            ],
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }
}
