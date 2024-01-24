<?php

namespace App\Admin\Account\State\Provider;

use ApiPlatform\Doctrine\Orm\Paginator;
use ApiPlatform\Doctrine\Orm\State\CollectionProvider;
use ApiPlatform\Doctrine\Orm\State\ItemProvider;
use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\TraversablePaginator;
use ApiPlatform\State\ProviderInterface;
use App\Admin\Account\ApiResource\AccountLogResource;
use App\Core\Account\Entity\Account;
use App\Admin\Account\ApiResource\AccountResource;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final class AccountLogProvider implements ProviderInterface
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
            /** @var Account[] $entities */
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
        $account = $entity->getOwner();

        $accountDto = new AccountResource(
            $account->getLogin(),
            $account->getPassword(),
            $account->getTradeServer(),
            $account->getMtVersion(),
            $account->getBalance(),
        );
        $accountDto->setId($account->getId());

        $dto = new AccountLogResource(
            $entity->getType(),
            $entity->getData(),
            $accountDto,
            $entity->getCopyDefinition(),
        );

        $dto->setId($entity->getId());
        return $dto;
    }
}
