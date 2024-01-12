<?php

namespace App\Admin\Account\State\Provider;

use ApiPlatform\Doctrine\Orm\Paginator;
use ApiPlatform\Doctrine\Orm\State\CollectionProvider;
use ApiPlatform\Doctrine\Orm\State\ItemProvider;
use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\TraversablePaginator;
use ApiPlatform\State\ProviderInterface;
use App\Admin\Account\ApiResource\AccountResource;
use App\Admin\Account\ApiResource\CopyDefinitionResource;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final class CopyDefinitionProvider implements ProviderInterface
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
            /** @var CopyDefinitionResource[] $entities */
            $entities = $this->collectionProvider->provide($operation, $uriVariables, $context);
            assert($entities instanceof Paginator);

            $dtos = [];

            foreach ($entities as $entity) {
                $dtos[] = $this->mapEntityToDto($entity);
            }

            return new TraversablePaginator(
                new \ArrayIterator($dtos),
                $entities->getCurrentPage(),
                $entities->getItemsPerPage(),
                $entities->getTotalItems()
            );
        }

        $entity = $this->itemProvider->provide($operation, $uriVariables, $context);

        if (!$entity) {
            return null;
        }

        return $this->mapEntityToDto($entity);
    }

    private function mapEntityToDto(object $entity): object
    {
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
            $sourceAccountResource,
            $targetAccountResource,
        );

        $dto->setId($entity->getId());
        return $dto;
    }
}
