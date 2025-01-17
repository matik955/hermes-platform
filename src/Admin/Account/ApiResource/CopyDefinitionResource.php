<?php

namespace App\Admin\Account\ApiResource;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Core\Account\Entity\Account;
use App\Core\Account\Entity\CopyDefinition;
use App\Admin\Account\State\Provider\CopyDefinitionProvider;
use App\Core\Account\Interface\AccountInterface;
use App\Core\Account\Interface\CopyDefinitionInterface;
use DateTime;
use App\Core\Account\Validator as AccountAssert;

#[ApiResource(
    shortName: 'CopyDefinition',
    operations: [
        new Get(
            uriTemplate: '/admin/copy-definitions/{id}',
            name: 'admin_get_single_copy_definition',
        ),
        new GetCollection(
            uriTemplate: '/admin/copy-definitions',
            name: 'admin_get_copy_definition_collection'
        ),
        new Delete(
            uriTemplate: '/admin/copy-definitions/{id}',
            name: 'admin_delete_copy_definition'
        )
    ],
    paginationItemsPerPage: 10,
    provider: CopyDefinitionProvider::class,
    stateOptions: new Options(entityClass: CopyDefinition::class)
)]
#[AccountAssert\UniqueCopyDefinitionAccount]
class CopyDefinitionResource implements CopyDefinitionInterface
{
    #[ApiProperty(writable: false, identifier: true)]
    private ?int $id = null;

    private bool $active = true;

    private bool $archived = false;

    private DateTime $createdAt;

    private ?DateTime $archivedAt = null;

    private ?AccountResource $sourceAccount;

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
