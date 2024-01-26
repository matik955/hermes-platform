<?php

namespace App\Core\System\Entity;

use App\Core\Resource\Model\ResourceInterface;
use App\Core\System\Repository\SystemLogRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use DateTime;

#[ORM\Entity(repositoryClass: SystemLogRepository::class)]
#[ORM\Table(name: "system_log")]
class SystemLog implements ResourceInterface
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

    public function __construct(
        string  $type,
        array   $data,
    )
    {
        $this->type = $type;
        $this->data = $data;
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

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
