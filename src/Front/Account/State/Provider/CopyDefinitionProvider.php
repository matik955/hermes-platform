<?php

namespace App\Front\Account\State\Provider;

use ApiPlatform\Doctrine\Orm\State\CollectionProvider;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Core\Account\Entity\CopyDefinition;
use App\Front\Account\ApiResource\AccountResource;
use App\Front\Account\ApiResource\CopyDefinitionResource;
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
            $sourceAccount = $entity->getSourceAccount();
            $sourceAccountResource = new AccountResource(
                $sourceAccount->getLogin(),
                $sourceAccount->getPassword(),
                $sourceAccount->getTradeServer(),
                $sourceAccount->getMtVersion(),
                $sourceAccount->getBalance(),
            );

            $sourceAccountResource->setId($sourceAccount->getId());

            $targetAccount = $entity->getTargetAccount();
            $targetAccountResource = new AccountResource(
                $targetAccount->getLogin(),
                $targetAccount->getPassword(),
                $targetAccount->getTradeServer(),
                $targetAccount->getMtVersion(),
                $targetAccount->getBalance(),
            );

            $targetAccountResource->setId($targetAccount->getId());


            $dto = new CopyDefinitionResource(
                $entity->getId(),
                $entity->isActive(),
                $entity->isArchived(),
                $entity->getCreatedAt(),
                $sourceAccountResource,
                $targetAccountResource,
            );

            $dtos[] = $dto;
        }

        return $dtos;
    }
}
