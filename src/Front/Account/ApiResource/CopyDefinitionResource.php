<?php

namespace App\Front\Account\ApiResource;

use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use App\Core\Account\Entity\CopyDefinition;
use App\Front\Account\State\Processor\CopyDefinitionProcessor;
use App\Front\Account\State\Provider\CopyDefinitionProvider;
use DateTime;

#[ApiResource(
    shortName: 'CopyDefinition',
    operations: [
        new Get(
            uriTemplate: '/front/copy-definitions/{id}',
            name: 'front_get_single_copy_definition',
        ),
        new GetCollection(
            uriTemplate: '/front/copy-definitions',
            name: 'front_get_copy_definition_collection'
        ),
        new Delete(
            uriTemplate: '/front/copy-definitions/{id}',
            name: 'front_delete_copy_definition'
        )
    ],
    paginationItemsPerPage: 10,
    provider: CopyDefinitionProvider::class,
    stateOptions: new Options(entityClass: CopyDefinition::class)
)]
#[ApiResource(
    uriTemplate: '/front/accounts/{accountId}/copy-definitions',
    shortName: 'CopyDefinition',
    operations: [
        new GetCollection(
            name: 'front_get_account_copy_definition_collection',
            provider: CopyDefinitionProvider::class
        ),
        new Post(
            read: false,
            name: 'front_add_account_copy_definition',
            processor: CopyDefinitionProcessor::class
        ),
    ],
    uriVariables: [
        'accountId' => new Link(toProperty: 'sourceAccount', fromClass: AccountResource::class),
    ],
    stateOptions: new Options(entityClass: CopyDefinition::class)
)]
#[ApiFilter(BooleanFilter::class, properties: ['active'])]
#[ApiFilter(SearchFilter::class, properties: [
    'phrase' => 'partial',
    'sourceAccount' => 'exact',
    'targetAccount' => 'exact'
])]
class CopyDefinitionResource
{
    #[ApiProperty(writable: false, identifier: true)]
    private ?int $id = null;

    private bool $active = true;

    private bool $archived = false;

    private DateTime $createdAt;

    private ?DateTime $archivedAt = null;

    #[ApiProperty(readableLink: true)]
    private ?AccountResource $sourceAccount;

    #[ApiProperty(readableLink: true)]
    private ?AccountResource $targetAccount;

    public function __construct(
        ?AccountResource $sourceAccount = null,
        ?AccountResource $targetAccount = null,
    )
    {
        $this->sourceAccount = $sourceAccount;
        $this->targetAccount = $targetAccount;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function isArchived(): bool
    {
        return $this->archived;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getArchivedAt(): ?DateTime
    {
        return $this->archivedAt;
    }

    public function getSourceAccount(): ?AccountResource
    {
        return $this->sourceAccount;
    }

    public function getTargetAccount(): ?AccountResource
    {
        return $this->targetAccount;
    }
}
