<?php

namespace App\Front\User\State\Provider;

use ApiPlatform\Doctrine\Orm\State\CollectionProvider;
use ApiPlatform\Doctrine\Orm\State\ItemProvider;
use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Front\User\ApiResource\UserResource;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final class UserProvider implements ProviderInterface
{
    public function __construct(
        #[Autowire(service: CollectionProvider::class)]
        private readonly ProviderInterface $collectionProvider,
        #[Autowire(service: ItemProvider::class)]
        private readonly ProviderInterface $itemProvider,
    )
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if ($operation instanceof CollectionOperationInterface) {
            /** @var UserResource[] $entities */
            $entities = $this->collectionProvider->provide($operation, $uriVariables, $context);

            $dtos = [];

            foreach ($entities as $entity) {
                $dtos[] = $this->mapEntityToDto($entity);
            }

            return $dtos;
        }

        $entity = $this->itemProvider->provide($operation, $uriVariables, $context);

        if (!$entity) {
            return null;
        }

        return $this->mapEntityToDto($entity);
    }

    private function mapEntityToDto(object $entity): object
    {
        $dto = new UserResource(
            $entity->getEmail(),
            $entity->getPassword(),
            $entity->getRoles()
        );

        $dto->setId($entity->getId());
        return $dto;
    }
}
