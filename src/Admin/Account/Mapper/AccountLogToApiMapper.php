<?php

namespace App\Admin\Account\Mapper;

use App\Admin\Account\ApiResource\AccountLogResource;
use App\Admin\Account\ApiResource\AccountResource;
use App\Core\Account\Entity\AccountLog;
use App\Core\Account\Repository\AccountRepository;
use Symfonycasts\MicroMapper\AsMapper;
use Symfonycasts\MicroMapper\MapperInterface;
use Symfonycasts\MicroMapper\MicroMapperInterface;

#[AsMapper(from: AccountLog::class, to: AccountLogResource::class)]
class AccountLogToApiMapper implements MapperInterface
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
        assert($entity instanceof AccountLog);

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
//            $this->microMapper->map($entity->getOwner(), AccountResource::class, [
//                MicroMapperInterface::MAX_DEPTH => 0,
//            ]),
            $entity->getCopyDefinition(),
        );

        $dto->setId($entity->getId());

        return $dto;
    }

    public function populate(object $from, object $to, array $context): object
    {
        $entity = $from;
        $dto = $to;
        assert($entity instanceof AccountLog);
        assert($dto instanceof AccountLogResource);

        return $dto;
    }
}
