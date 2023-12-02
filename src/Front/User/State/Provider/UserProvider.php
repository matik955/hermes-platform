<?php

namespace App\Front\User\State\Provider;

use ApiPlatform\Doctrine\Orm\State\CollectionProvider;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Core\User\Entity\User;
use App\Front\User\ApiResource\UserResource;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final class UserProvider implements ProviderInterface
{
    public function __construct(
        #[Autowire(service: CollectionProvider::class)]
        private ProviderInterface $collectionProvider
    )
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        /** @var User[] $entities */
        $entities = $this->collectionProvider->provide($operation, $uriVariables, $context);

        $dtos = [];

        foreach ($entities as $entity) {
            $dto = new UserResource(
                $entity->getEmail(),
                $entity->getRoles(),
                $entity->getPassword()
            );

            $dto->setId($entity->getId());
            $dtos[] = $dto;
        }

        return $dtos;
    }
}
