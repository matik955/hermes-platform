<?php

namespace App\Front\Account\State\Provider;

use ApiPlatform\Doctrine\Orm\Paginator;
use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\Pagination;
use ApiPlatform\State\Pagination\TraversablePaginator;
use ApiPlatform\State\ProviderInterface;
use App\Core\Account\Entity\Account;
use App\Core\Account\Repository\AccountRepository;
use App\Front\Account\ApiResource\AccountResource;
use App\Front\User\ApiResource\UserResource;
use App\Front\User\Provider\FrontUserProvider;

final class AccountProvider implements ProviderInterface
{
    public function __construct(
        private readonly FrontUserProvider $userProvider,
        private readonly AccountRepository $accountRepository,
        private readonly Pagination $pagination,
    )
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $currentUser = $this->userProvider->getUser();

        if ($operation instanceof CollectionOperationInterface) {
            $currentPage = $this->pagination->getPage($context);
            $itemsPerPage = $this->pagination->getLimit($operation, $context);

            /** @var Account[] $entities */
            $entities = $this->accountRepository->getAccountsForUser(
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

        $entity = $this->accountRepository->findOneBy([
            'id' => $uriVariables['id'],
            'user' => $currentUser->getId()
        ]);

        if (!$entity) {
            return null;
        }

        return $this->mapEntityToDto($entity);
    }

    private function mapEntityToDto(object $entity): object
    {
        $user = $entity->getUser();

        $userDto = new UserResource(
            $user->getEmail(),
            $user->getPassword(),
            $user->getRoles(),
        );
        $userDto->setId($user->getId());

        $dto = new AccountResource(
            $entity->getLogin(),
            $entity->getPassword(),
            $entity->getTradeServer(),
            $entity->getMtVersion(),
            $userDto
        );

        $dto->setId($entity->getId());
        return $dto;
    }
}
