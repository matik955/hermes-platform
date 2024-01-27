<?php

namespace App\Front\Account\State\Provider;

use ApiPlatform\Doctrine\Orm\Paginator;
use ApiPlatform\Doctrine\Orm\State\ItemProvider;
use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\Pagination;
use ApiPlatform\State\Pagination\TraversablePaginator;
use ApiPlatform\State\ProviderInterface;
use App\Core\Account\Entity\CopyDefinition;
use App\Core\Account\Repository\CopyDefinitionRepository;
use App\Front\Account\ApiResource\AccountResource;
use App\Front\Account\ApiResource\CopyDefinitionResource;
use App\Front\User\Provider\FrontUserProvider;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final class CopyDefinitionProvider implements ProviderInterface
{
    public function __construct(
        #[Autowire(service: ItemProvider::class)]
        private readonly ProviderInterface $itemProvider,
        private readonly FrontUserProvider $userProvider,
        private readonly CopyDefinitionRepository $copyDefinitionRepository,
        private readonly Pagination $pagination,
    )
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if ($operation instanceof CollectionOperationInterface) {
            $currentUser = $this->userProvider->getUser();

            $currentPage = $this->pagination->getPage($context);
            $itemsPerPage = $this->pagination->getLimit($operation, $context);

            /** @var CopyDefinition[] $entities */
            $entities = $this->copyDefinitionRepository->getPaginatorForUser(
                $currentUser->getId(),
                $currentPage,
                $itemsPerPage,
                $context['filters'] ??= []
            );
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
        );

        $sourceAccountResource->setId($sourceAccount->getId());

        $targetAccount = $entity->getTargetAccount();
        $targetAccountResource = new AccountResource(
            $targetAccount->getLogin(),
            $targetAccount->getPassword(),
            $targetAccount->getTradeServer(),
            $targetAccount->getMtVersion(),
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
