<?php

namespace App\Admin\Account\ApiResource;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use App\Admin\Account\State\Processor\AccountLogProcessor;
use App\Admin\Account\State\Provider\AccountLogProvider;
use App\Core\Account\Entity\AccountLog;
use App\Core\Api\State\EntityClassDtoStateProcessor;
use App\Core\Api\State\EntityToDtoStateProvider;

#[ApiResource(
    shortName: 'AccountLog',
    operations: [
        new Get(
            uriTemplate: '/admin/account-logs/{id}',
            name: 'admin_get_single_account_log'
        ),
    ],
    paginationItemsPerPage: 10,
    provider: AccountLogProvider::class,
    processor: AccountLogProcessor::class,
    stateOptions: new Options(entityClass: AccountLog::class)
)]
#[ApiResource(
    uriTemplate: '/admin/accounts/{accountId}/account-logs',
    shortName: 'AccountLog',
    operations: [
        new GetCollection(
            name: 'admin_get_account_log_collection'
        ),
        new Post(
            read: false,
            name: 'admin_add_account_log',
            processor: AccountLogProcessor::class,
        ),
    ],
    uriVariables: [
        'accountId' => new Link(toProperty: 'owner', fromClass: AccountResource::class),
    ],
    paginationItemsPerPage: 10,
    provider: AccountLogProvider::class,
    stateOptions: new Options(entityClass: AccountLog::class)
)]
class AccountLogResource
{
    #[ApiProperty(writable: false, identifier: true)]
    private ?int $id = null;

    private string $type;

    private array $data;

    private \DateTime $createdAt;

    private AccountResource $owner;

    public ?CopyDefinitionResource $copyDefinition;

    public function __construct(
        string  $type,
        array   $data,
        AccountResource $owner,
        ?CopyDefinitionResource $copyDefinition = null
    )
    {
        $this->type = $type;
        $this->data = $data;
        $this->owner = $owner;
        $this->copyDefinition = $copyDefinition;
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

    public function getOwner(): AccountResource
    {
        return $this->owner;
    }

    public function getCopyDefinition(): ?CopyDefinitionResource
    {
        return $this->copyDefinition;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
}
