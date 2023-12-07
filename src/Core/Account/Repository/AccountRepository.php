<?php

namespace App\Core\Account\Repository;

use App\Core\Account\Entity\Account;
use App\Core\Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;

class AccountRepository extends EntityRepository
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, $entityManager->getClassMetadata(Account::class));
    }
}
