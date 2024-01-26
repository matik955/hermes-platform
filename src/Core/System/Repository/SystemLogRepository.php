<?php

namespace App\Core\System\Repository;

use App\Core\Doctrine\ORM\EntityRepository;
use App\Core\System\Entity\SystemLog;
use Doctrine\ORM\EntityManagerInterface;

class SystemLogRepository extends EntityRepository
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, $entityManager->getClassMetadata(SystemLog::class));
    }
}
