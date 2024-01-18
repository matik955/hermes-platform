<?php

namespace App\Admin\Account\ApiResource;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Core\Account\Entity\AccountLog;

#[ApiResource(
    shortName: 'AccountLog',
    stateOptions: new Options(entityClass: AccountLog::class),
)]
class AccountLogResource
{
    #[ApiProperty(writable: false, identifier: true)]
    private ?int $id = null;

    private string $type;

    private array $data;

    private \DateTime $createdAt;

    private AccountResource $owner;

    /**
     * @var array<int, CopyDefinitionResource>
     */
    #[ApiProperty(writable: false)]
    public array $sourceDefinitions = [];

    public function __construct(
        string  $type,
        array   $data,
        AccountResource $owner
    )
    {
        $this->type = $type;
        $this->data = $data;
        $this->owner = $owner;
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

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
}
