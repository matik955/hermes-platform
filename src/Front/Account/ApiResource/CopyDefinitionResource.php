<?php

namespace App\Front\Account\ApiResource;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use App\Core\Account\Entity\CopyDefinition;
use App\Front\Account\State\Provider\CopyDefinitionProvider;
use Symfony\Component\Serializer\Annotation\Groups;
use DateTime;

#[ApiResource(
    shortName: 'CopyDefinition',
    operations: [
        new Get(
            uriTemplate: '/copy-definitions/{id}',
            name: 'GetSingleCopyDefinition',
        ),
        new GetCollection(
            uriTemplate: '/copy-definitions',
            name: 'GetCopyDefinitions'
        )
    ],
    provider: CopyDefinitionProvider::class,
    stateOptions: new Options(entityClass: CopyDefinition::class)
)]
#[ApiResource(
    uriTemplate: '/accounts/{accountId}/copy-definitions',
    shortName: 'CopyDefinition',
    operations: [
        new GetCollection(provider: CopyDefinitionProvider::class)
    ],
    uriVariables: [
        'accountId' => new Link(toProperty: 'sourceAccount', fromClass: AccountResource::class),
    ],
    stateOptions: new Options(entityClass: CopyDefinition::class)
)]
class CopyDefinitionResource
{
    #[ApiProperty(readable: false, writable: false, identifier: true)]
    private int $id;

    private bool $active;

    private bool $archived;

    private DateTime $createdAt;

    private ?DateTime $archivedAt = null;

    private ?AccountResource $sourceAccount;

    private ?AccountResource $targetAccount;

    public function __construct(
        int $id,
        bool $active,
        bool $archived,
        DateTime $createdAt,
        ?AccountResource $sourceAccount = null,
        ?AccountResource $targetAccount = null,
    )
    {
        $this->id = $id;
        $this->active = $active;
        $this->archived = $archived;
        $this->createdAt = $createdAt;
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
