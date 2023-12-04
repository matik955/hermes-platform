<?php

namespace App\Core\Order\Entity;

use App\Core\Account\Entity\Account;
use App\Core\Account\Entity\CopyDefinition;
use App\Core\Resource\Model\ResourceInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use DateTime;

#[ORM\Entity()]
#[ORM\Table(name: "hermes_order")]
class Order implements ResourceInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(
        name: 'source_mt_order_id',
        type: 'integer',
        nullable: true
    )]
    private ?int $sourceMtOrderId = null;

    #[ORM\Column(type: 'bigint')]
    private string $positionId;

    #[ORM\Column(type: 'string')]
    private string $tradeSymbol;

    #[ORM\Column(type: 'string')]
    private string $comment;

    #[ORM\Column(name: 'created_at_mt', type: Types::DATETIME_MUTABLE)]
    private DateTime $createdAtMt;

    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(name: 'created_at', type: Types::DATETIME_MUTABLE)]
    private DateTime $createdAt;

    #[ORM\Column(name: 'expiration_mt', type: Types::DATETIME_MUTABLE)]
    private DateTime $expirationMt;

    #[ORM\Column(
        name: 'done_mt',
        type: Types::DATETIME_MUTABLE,
        nullable: true
    )]
    private ?DateTime $doneMt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTime $done = null;

    #[ORM\Column(type: 'string')]
    private string $type;

    #[ORM\Column(type: 'float')]
    private float $price;

    #[ORM\Column(name: 'stop_loss', type: 'float')]
    private float $stopLoss;

    #[ORM\Column(type: 'float')]
    private float $takeProfit;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $profit = null;

    #[ORM\ManyToOne(targetEntity: Account::class, inversedBy: 'orders')]
    #[ORM\JoinColumn(name: 'account_id', referencedColumnName: 'id')]
    private Account $account;

    #[ORM\ManyToOne(targetEntity: CopyDefinition::class, inversedBy: 'orders')]
    #[ORM\JoinColumn(name: 'copy_definition_id', referencedColumnName: 'id', nullable: true)]
    private ?CopyDefinition $copyDefinition;

    public function __construct(
        int $id,
        string $positionId,
        string $tradeSymbol,
        string $comment,
        DateTime $createdAtMt,
        DateTime $expirationMt,
        string $type,
        float $price,
        float $stopLoss,
        float $takeProfit,
        Account $account,
        ?CopyDefinition $copyDefinition = null
    )
    {
        $this->id = $id;
        $this->positionId = $positionId;
        $this->tradeSymbol = $tradeSymbol;
        $this->comment = $comment;
        $this->createdAtMt = $createdAtMt;
        $this->expirationMt = $expirationMt;
        $this->type = $type;
        $this->price = $price;
        $this->stopLoss = $stopLoss;
        $this->takeProfit = $takeProfit;
        $this->account = $account;
        $this->copyDefinition = $copyDefinition;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getSourceMtOrderId(): ?int
    {
        return $this->sourceMtOrderId;
    }

    public function getPositionId(): string
    {
        return $this->positionId;
    }

    public function getTradeSymbol(): string
    {
        return $this->tradeSymbol;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function getCreatedAtMt(): DateTime
    {
        return $this->createdAtMt;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getExpirationMt(): DateTime
    {
        return $this->expirationMt;
    }

    public function getDoneMt(): ?DateTime
    {
        return $this->doneMt;
    }

    public function getDone(): ?DateTime
    {
        return $this->done;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getStopLoss(): float
    {
        return $this->stopLoss;
    }

    public function getTakeProfit(): float
    {
        return $this->takeProfit;
    }

    public function getProfit(): ?float
    {
        return $this->profit;
    }

    public function getAccount(): Account
    {
        return $this->account;
    }

    public function getCopyDefinition(): CopyDefinition
    {
        return $this->copyDefinition;
    }
}
