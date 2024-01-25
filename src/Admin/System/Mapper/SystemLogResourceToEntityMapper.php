<?php

namespace App\Admin\System\Mapper;

use App\Admin\System\ApiResource\SystemLogResource;
use App\Core\System\Entity\SystemLog;
use App\Core\System\Repository\SystemLogRepository;
use Symfonycasts\MicroMapper\AsMapper;
use Symfonycasts\MicroMapper\MapperInterface;

#[AsMapper(from: SystemLogResource::class, to: SystemLog::class)]
class SystemLogResourceToEntityMapper implements MapperInterface
{
    public function __construct(
        private readonly SystemLogRepository $systemLogRepository,
    )
    {
    }

    public function load(object $from, string $toClass, array $context): object
    {
        $dto = $from;
        assert($dto instanceof SystemLogResource);

        if ($dto->getId()) {
            $entity = $this->systemLogRepository->find($dto->getId());
        } else {
            $entity = new SystemLog(
                $dto->getType(),
                $dto->getData(),
            );
        }

        if (!$entity) {
            throw new \Exception('System Log not found');
        }

        return $entity;
    }

    public function populate(object $from, object $to, array $context): object
    {
        $dto = $from;
        $entity = $to;
        assert($dto instanceof SystemLogResource);
        assert($entity instanceof SystemLog);

        return $entity;
    }
}
