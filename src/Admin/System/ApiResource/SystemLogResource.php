<?php

namespace App\Admin\System\ApiResource;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Core\Api\State\EntityClassDtoStateProcessor;
use App\Core\Api\State\EntityToDtoStateProvider;
use App\Core\System\Entity\SystemLog;

#[ApiResource(
    shortName: 'SystemLog',
    operations: [
        new Get(
            uriTemplate: '/admin/system-logs/{id}',
            name: 'admin_get_single_system_log'
        ),
        new GetCollection(
            uriTemplate: '/admin/system-logs',
            name: 'admin_get_system_log_collection'
        ),
        new Post(
            uriTemplate: '/admin/system-logs',
            name: 'admin_add_system_log',
        ),
    ],
    paginationItemsPerPage: 10,
    provider: EntityToDtoStateProvider::class,
    processor: EntityClassDtoStateProcessor::class,
    stateOptions: new Options(entityClass: SystemLog::class)
)]
class SystemLogResource
{
    #[ApiProperty(writable: false, identifier: true)]
    private ?int $id = null;

    private string $type;

    private array $data;

    private \DateTime $createdAt;

    public function __construct(
        string  $type,
        array   $data
    )
    {
        $this->type = $type;
        $this->data = $data;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
}
