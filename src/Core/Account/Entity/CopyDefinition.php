<?php

namespace App\Core\Account\Entity;

use App\Core\Account\Repository\CopyDefinitionRepository;
use App\Core\Order\Entity\Order;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use DateTime;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: CopyDefinitionRepository::class)]
#[ORM\Table(name: "copy_definition")]
class CopyDefinition
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'boolean')]
    private bool $active = true;

    #[ORM\Column(type: 'boolean')]
    private bool $archived = false;

    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(name: 'created_at', type: Types::DATETIME_MUTABLE)]
    private DateTime $createdAt;

    #[ORM\Column(
        name: 'archived_at',
        type: Types::DATETIME_MUTABLE,
        nullable: true
    )]
    private ?DateTime $archivedAt = null;

    #[ORM\ManyToOne(targetEntity: Account::class, inversedBy: 'sourceDefinitions')]
    #[ORM\JoinColumn(name: 'source_account_id', referencedColumnName: 'id')]
    private Account $sourceAccount;

    #[ORM\ManyToOne(targetEntity: Account::class, inversedBy: 'targetDefinitions')]
    #[ORM\JoinColumn(name: 'target_account_id', referencedColumnName: 'id')]
    private Account $targetAccount;

    #[ORM\OneToMany(mappedBy: 'copyDefinition', targetEntity: Order::class)]
    private Collection $orders;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
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

    public function getSourceAccount(): Account
    {
        return $this->sourceAccount;
    }

    public function getTargetAccount(): Account
    {
        return $this->targetAccount;
    }

    public function getOrders(): Collection
    {
        return $this->orders;
    }
}
