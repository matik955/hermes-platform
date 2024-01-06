<?php

namespace App\Admin\Account\State\Provider;

use ApiPlatform\Doctrine\Orm\State\CollectionProvider;
use ApiPlatform\Doctrine\Orm\State\ItemProvider;
use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Core\Account\Entity\Account;
use App\Admin\Account\ApiResource\AccountResource;
use App\Admin\User\ApiResource\UserResource;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final class AccountProvider implements ProviderInterface
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
            $entity->getBalance(),
            $userDto
        );

        $dto->setId($entity->getId());
        return $dto;
    }
}
