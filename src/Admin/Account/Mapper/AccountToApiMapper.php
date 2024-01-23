<?php

namespace App\Admin\Account\Mapper;

use App\Admin\Account\ApiResource\AccountResource;
use App\Admin\User\ApiResource\UserResource;
use App\Core\Account\Entity\Account;
use App\Core\Account\Repository\AccountRepository;
use Symfonycasts\MicroMapper\AsMapper;
use Symfonycasts\MicroMapper\MapperInterface;
use Symfonycasts\MicroMapper\MicroMapperInterface;

#[AsMapper(from: Account::class, to: AccountResource::class)]
class AccountToApiMapper implements MapperInterface
{
    public function __construct(
        private readonly MicroMapperInterface $microMapper,
        private AccountRepository $accountRepository
    )
    {
    }

    public function load(object $from, string $toClass, array $context): object
    {
        $entity = $from;
        assert($entity instanceof Account);

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
            $userDto,
        );

        $dto->setId($entity->getId());
        return $dto;
    }

    public function populate(object $from, object $to, array $context): object
    {
        $entity = $from;
        $dto = $to;
        assert($entity instanceof Account);
        assert($dto instanceof AccountResource);

        return $dto;
    }
}
