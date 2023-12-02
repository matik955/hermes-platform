<?php

namespace App\Front\Account\State\Provider;

use ApiPlatform\Doctrine\Orm\State\CollectionProvider;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Core\Account\Entity\Account;
use App\Core\Account\Entity\CopyDefinition;
use App\Front\Account\ApiResource\AccountResource;
use App\Front\Account\ApiResource\CopyDefinitionResource;
use App\Front\User\ApiResource\UserResource;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final class CopyDefinitionProvider implements ProviderInterface
{
    public function __construct(
        #[Autowire(service: CollectionProvider::class)]
        private ProviderInterface $collectionProvider
    )
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        /** @var CopyDefinition[] $entities */
        $entities = $this->collectionProvider->provide($operation, $uriVariables, $context);

        $dtos = [];

        foreach ($entities as $entity) {
            $dto = new CopyDefinitionResource();
            $dto->setId($entity->getId());

            $dtos[] = $dto;
        }

        return $dtos;
    }
}
