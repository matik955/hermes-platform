<?php

namespace App\Admin\Account\Mapper;

use App\Admin\Account\ApiResource\AccountLogResource;
use App\Core\Account\Entity\AccountLog;
use App\Core\Account\Repository\AccountLogRepository;
use App\Core\Account\Repository\AccountRepository;
use Symfonycasts\MicroMapper\AsMapper;
use Symfonycasts\MicroMapper\MapperInterface;
use Symfonycasts\MicroMapper\MicroMapperInterface;

#[AsMapper(from: AccountLogResource::class, to: AccountLog::class)]
class AccountLogResourceToEntityMapper implements MapperInterface
{
    public function __construct(
        private readonly MicroMapperInterface $microMapper,
        private readonly AccountLogRepository $accountLogRepository,
        private readonly AccountRepository $accountRepository
    )
    {
    }

    public function load(object $from, string $toClass, array $context): object
    {
        $dto = $from;
        assert($dto instanceof AccountLogResource);

        if ($dto->getId()) {
            $entity = $this->accountLogRepository->find($dto->getId());
        } else {
            $owner = $this->accountRepository->findOneBy(['id' => $dto->getOwner()->getId()]);

            $entity = new AccountLog(
                $dto->getType(),
                $dto->getData(),
                $owner,
                $dto->getCopyDefinition(),
            );
        }

        if (!$entity) {
            throw new \Exception('Account Log not found');
        }

        return $entity;
    }

    public function populate(object $from, object $to, array $context): object
    {
        $dto = $from;
        $entity = $to;
        assert($dto instanceof AccountLogResource);
        assert($entity instanceof AccountLog);

        return $entity;
    }
}
