<?php

namespace App\Tests\Api\Admin\System;

use App\Admin\System\ApiResource\SystemLogResource;
use App\Tests\Api\AbstractTest;
use App\Tests\Factory\SystemLogFactory;
use App\Tests\Factory\UserFactory;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class SystemLogTest extends AbstractTest
{
    use ResetDatabase, Factories;

    public function testGetCollection(): void
    {
        UserFactory::createOne(['email' => 'admin@example.com', 'password' => 'admin']);

        SystemLogFactory::createMany(50);

        $response = static::createClientWithCredentials()->request('GET', '/api/admin/system-logs');

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        // Asserts that the returned JSON is a superset of this one
        $this->assertJsonContains([
            '@context' => '/api/contexts/SystemLog',
            '@id' => '/api/admin/system-logs',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 50,
            'hydra:view' => [
                '@id' => '/api/admin/system-logs?page=1',
                '@type' => 'hydra:PartialCollectionView',
                'hydra:first' => '/api/admin/system-logs?page=1',
                'hydra:last' => '/api/admin/system-logs?page=5',
                'hydra:next' => '/api/admin/system-logs?page=2',
            ],
        ]);

        // Because test fixtures are automatically loaded between each test, you can assert on them
        $this->assertCount(10, $response->toArray()['hydra:member']);
        $this->assertMatchesResourceCollectionJsonSchema(SystemLogResource::class);
    }

    public function testGetSingleItem(): void
    {
        UserFactory::createOne(['email' => 'admin@example.com', 'password' => 'admin']);

        $systemLog = SystemLogFactory::createOne(['type' => 'unknown_error']);

        $systemLogId = $systemLog->getId();
        static::createClientWithCredentials()->request('GET', '/api/admin/system-logs/' . $systemLogId);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        // Asserts that the returned JSON is a superset of this one
        $this->assertJsonContains([
            "@context" => "/api/contexts/SystemLog",
            "@id" => "/api/admin/system-logs/" . $systemLogId,
            "@type" => "SystemLog",
            "id" => $systemLogId,
            "type" => "unknown_error",
            "data" => []
        ]);

        $this->assertMatchesResourceItemJsonSchema(SystemLogResource::class);
    }

    public function testCreateSystemLog(): void
    {
        $response = static::createClient()->request('POST', '/api/admin/system-logs', [
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
        $this->assertMatchesRegularExpression('~^/api/admin/system-logs/\d+$~', $response->toArray()['@id']);
        $this->assertMatchesResourceItemJsonSchema(SystemLogResource::class);
    }
}
