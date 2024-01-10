<?php

namespace App\Front\Account\State\Provider;

use ApiPlatform\Doctrine\Orm\State\CollectionProvider;
use ApiPlatform\Doctrine\Orm\State\ItemProvider;
use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Core\Account\Entity\Account;
use App\Core\Account\Repository\AccountRepository;
use App\Front\Account\ApiResource\AccountResource;
use App\Front\User\ApiResource\UserResource;
use App\Front\User\Provider\FrontUserProvider;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final class AccountProvider implements ProviderInterface
{
    public function __construct(
        private readonly FrontUserProvider $userProvider,
        private readonly AccountRepository $accountRepository
    )
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $currentUser = $this->userProvider->getUser();

        if ($operation instanceof CollectionOperationInterface) {
            /** @var Account[] $entities */
            $entities = $currentUser->getAccounts();

            $dtos = [];

            foreach ($entities as $entity) {
                $dtos[] = $this->mapEntityToDto($entity);
            }

            return $dtos;
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
