<?php

namespace App\Front\Account\ApiResource;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Core\Account\Entity\CopyDefinition;
use App\Front\Account\State\Provider\CopyDefinitionProvider;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    shortName: 'CopyDefinition',
    operations: [
        new Get(),
        new GetCollection()
    ],
    provider: CopyDefinitionProvider::class,
    stateOptions: new Options(entityClass: CopyDefinition::class)
)]
class CopyDefinitionResource
{
    #[ApiProperty(readable: false, writable: false, identifier: true)]
    private ?int $id = null;

    private bool $active = true;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }
}
