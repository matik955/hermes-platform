<?php

namespace App\Core\Account\Entity;

use Doctrine\ORM\Mapping as ORM;

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
    private string $mtVersion;

    #[ORM\Column(type: 'float')]
    private float $balance;

    #[ORM\Column(type: 'boolean')]
    private bool $archived;
}
