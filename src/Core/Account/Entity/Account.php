<?php

namespace App\Core\Account\Entity;

use App\Core\Order\Entity\Order;
use App\Core\User\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use DateTime;

#[ORM\Entity()]
#[ORM\Table(name: "account")]
class Account
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private string $login;

    #[ORM\Column(type: 'string')]
    private string $password;

    #[ORM\Column(type: 'string')]
    private string $tradeServer;

    #[ORM\Column(type: 'integer')]
    private int $mtVersion;

    #[ORM\Column(type: 'float')]
    private float $balance;

    #[ORM\Column(type: 'boolean')]
    private bool $archived;

    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(name: 'created_at', type: Types::DATETIME_MUTABLE)]
    private DateTime $createdAt;

    #[ORM\Column(
        name: 'archived_at',
        type: Types::DATETIME_MUTABLE,
        nullable: true
    )]
    private ?DateTime $archivedAt = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'accounts')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private User $user;

    #[ORM\OneToMany(mappedBy: 'sourceAccount', targetEntity: CopyDefinition::class)]
    private Collection $sourceDefinitions;

    #[ORM\OneToMany(mappedBy: 'targetAccount', targetEntity: CopyDefinition::class)]
    private Collection $targetDefinitions;

    #[ORM\OneToMany(mappedBy: 'account', targetEntity: Order::class)]
    private Collection $orders;

    public function __construct()
    {
        $this->sourceDefinitions = new ArrayCollection();
        $this->targetDefinitions = new ArrayCollection();
        $this->orders = new ArrayCollection();
    }
}
