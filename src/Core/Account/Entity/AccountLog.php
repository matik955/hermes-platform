<?php

namespace App\Core\Account\Entity;

use App\Core\Account\Repository\AccountLogRepository;
use App\Core\Resource\Model\ResourceInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use DateTime;

#[ORM\Entity(repositoryClass: AccountLogRepository::class)]
#[ORM\Table(name: "account_log")]
class AccountLog implements ResourceInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string')]
    private string $type;

    #[ORM\Column(type: 'json')]
    private array $data;

    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(name: 'created_at', type: Types::DATETIME_MUTABLE)]
    private DateTime $createdAt;

    #[ORM\ManyToOne(targetEntity: Account::class, inversedBy: 'logs')]
    #[ORM\JoinColumn(name: 'account_id', referencedColumnName: 'id')]
    private Account $owner;

    public function __construct(
        string  $type,
        array   $data,
        Account $owner
    )
    {
        $this->type = $type;
        $this->data = $data;
        $this->owner = $owner;
        $this->createdAt = new DateTime();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getOwner(): Account
    {
        return $this->owner;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

}
