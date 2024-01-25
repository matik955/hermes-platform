<?php

namespace App\Admin\System\Mapper;

use App\Admin\System\ApiResource\SystemLogResource;
use App\Core\System\Entity\SystemLog;
use Symfonycasts\MicroMapper\AsMapper;
use Symfonycasts\MicroMapper\MapperInterface;

#[AsMapper(from: SystemLog::class, to: SystemLogResource::class)]
class SystemLogToApiMapper implements MapperInterface
{
    public function load(object $from, string $toClass, array $context): object
    {
        $entity = $from;
        assert($entity instanceof SystemLog);

        $dto = new SystemLogResource(
            $entity->getType(),
            $entity->getData(),
        );

        $dto->setId($entity->getId());

        return $dto;
    }

    public function populate(object $from, object $to, array $context): object
    {
        $entity = $from;
        $dto = $to;
        assert($entity instanceof SystemLog);
        assert($dto instanceof SystemLogResource);

        return $dto;
    }
}
