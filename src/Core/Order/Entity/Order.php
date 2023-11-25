<?php

namespace App\Core\Order\Entity;

use App\Core\Account\Entity\Account;
use App\Core\Account\Entity\CopyDefinition;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use DateTime;

#[ORM\Entity()]
#[ORM\Table(name: "order")]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(name: 'source_mt_order_id', type: 'integer')]
    private int $sourceMtOrderId;

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

    #[ORM\Column(name: 'done_mt', type: Types::DATETIME_MUTABLE)]
    private DateTime $doneMt;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private DateTime $done;

    #[ORM\Column(type: 'string')]
    private string $type;

    #[ORM\Column(type: 'float')]
    private float $price;

    #[ORM\Column(name: 'stop_loss', type: 'float')]
    private float $stopLoss;

    #[ORM\Column(type: 'float')]
    private float $takeProfit;

    #[ORM\Column(type: 'float')]
    private float $profit;

    #[ORM\ManyToOne(targetEntity: Account::class, inversedBy: 'orders')]
    #[ORM\JoinColumn(name: 'account_id', referencedColumnName: 'id')]
    private Account $account;

    #[ORM\ManyToOne(targetEntity: CopyDefinition::class, inversedBy: 'orders')]
    #[ORM\JoinColumn(name: 'copy_definition_id', referencedColumnName: 'id')]
    private CopyDefinition $copyDefinition;
}
