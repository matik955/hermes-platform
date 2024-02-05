<?php

namespace App\Core\Account\Entity;

use App\Core\Account\Repository\AccountRepository;
use App\Core\Order\Entity\Order;
use App\Core\Resource\Model\ResourceInterface;
use App\Core\User\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use DateTime;

#[ORM\Entity(repositoryClass: AccountRepository::class)]
#[ORM\Table(name: "account")]
class Account implements ResourceInterface
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

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $balance = null;

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

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'accounts')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private User $user;

    #[ORM\OneToMany(mappedBy: 'sourceAccount', targetEntity: CopyDefinition::class)]
    private Collection $sourceDefinitions;

    #[ORM\OneToMany(mappedBy: 'targetAccount', targetEntity: CopyDefinition::class)]
    private Collection $targetDefinitions;

    #[ORM\OneToMany(mappedBy: 'account', targetEntity: Order::class)]
    private Collection $orders;

    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: AccountLog::class)]
    private Collection $logs;

    public function __construct(
        string      $login,
        string      $password,
        string      $tradeServer,
        int         $mtVersion,
        User        $user,
        ?float      $balance = null,
        ?Collection $sourceDefinitions = null,
        ?Collection $targetDefinitions = null,
        ?Collection $orders = null,
        ?Collection $logs = null
    )
    {
        $this->login = $login;
        $this->password = $password;
        $this->tradeServer = $tradeServer;
        $this->mtVersion = $mtVersion;
        $this->balance = $balance;
        $this->user = $user;

        $this->sourceDefinitions = $sourceDefinitions ?? new ArrayCollection();
        $this->targetDefinitions = $targetDefinitions ?? new ArrayCollection();
        $this->orders = $orders ?? new ArrayCollection();
        $this->logs = $logs ?? new  ArrayCollection();
        $this->createdAt = new DateTime();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getTradeServer(): string
    {
        return $this->tradeServer;
    }

    public function getMtVersion(): int
    {
        return $this->mtVersion;
    }

    public function getBalance(): ?float
    {
        return $this->balance;
    }

    public function updateBalance(float $balance): void
    {
        $this->balance = $balance;
    }

    public function isArchived(): bool
    {
        return $this->archived;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }


    public function getArchivedAt(): ?DateTime
    {
        return $this->archivedAt;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getSourceDefinitions(): Collection
    {
        return $this->sourceDefinitions;
    }

    public function getTargetDefinitions(): Collection
    {
        return $this->targetDefinitions;
    }

    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function getLogs(): Collection
    {
        return $this->logs;
    }

    public function addLog(AccountLog $log): void
    {
        if (!$this->logs->contains($log)) {
            $this->logs->add($log);
        }
    }

    public function removeLog(AccountLog $log): void
    {
        $this->logs->removeElement($log);
    }
}
