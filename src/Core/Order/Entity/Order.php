<?php

namespace App\Core\Order\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTime;

#[ORM\Entity()]
#[ORM\Table(name: "order")]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'bigint')]
    private int $positionId;

    #[ORM\Column(type: 'string')]
    private string $tradeSymbol;

    #[ORM\Column(type: 'string')]
    private string $comment;

    #[ORM\Column(type: 'string')]
    private DateTime $expirationMt;

    #[ORM\Column(type: 'string')]
    private DateTime $doneMt;

    #[ORM\Column(type: 'string')]
    private DateTime $done;

    #[ORM\Column(type: 'string')]
    private string $type;

    #[ORM\Column(type: 'float')]
    private float $price;

    #[ORM\Column(type: 'float')]
    private float $stopLoss;

    #[ORM\Column(type: 'float')]
    private float $takeProfit;

    #[ORM\Column(type: 'float')]
    private float $profit;
}
