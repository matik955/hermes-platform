<?php

namespace App\Core\Account\Repository;

use App\Core\Account\Entity\AccountLog;
use App\Core\Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;

class AccountLogRepository extends EntityRepository
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, $entityManager->getClassMetadata(AccountLog::class));
    }
}
