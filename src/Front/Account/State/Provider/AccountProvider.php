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

final class AccountProvider implements ProviderInterface
{
    public function __construct(
        #[Autowire(service: CollectionProvider::class)]
        private ProviderInterface $collectionProvider
    )
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        /** @var Account[] $entities */
        $entities = $this->collectionProvider->provide($operation, $uriVariables, $context);

        $dtos = [];

        foreach ($entities as $entity) {
            $user = $entity->getUser();

            $userDto = new UserResource(
                $user->getEmail(),
                $user->getRoles(),
                $user->getPassword()
            );
            $userDto->setId($user->getId());

            $dto = new AccountResource(
                $entity->getLogin(),
                $entity->getPassword(),
                $entity->getTradeServer(),
                $entity->getMtVersion(),
                $entity->getBalance(),
                $userDto
            );

            $sourceDefinitions = [];

            /** @var CopyDefinition $sourceDefinition */
            foreach ($entity->getSourceDefinitions() as $sourceDefinition) {
                $copyDefinition = new CopyDefinitionResource(
                    $sourceDefinition->getId(),
                    $sourceDefinition->isActive(),
                    $sourceDefinition->isArchived(),
                    $sourceDefinition->getCreatedAt(),
                );
                $copyDefinition->setId($sourceDefinition->getId());

                $sourceDefinitions[] = $copyDefinition;
            }

            $dto->setSourceDefinitions($sourceDefinitions);

            $dto->setId($entity->getId());
            $dtos[] = $dto;
        }

        return $dtos;
    }
}
