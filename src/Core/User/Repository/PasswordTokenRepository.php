<?php

namespace App\Core\User\Repository;

use App\Core\Doctrine\ORM\EntityRepository;
use App\Core\User\Entity\PasswordToken;
use Doctrine\ORM\EntityManagerInterface;
class PasswordTokenRepository extends EntityRepository
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, $entityManager->getClassMetadata(PasswordToken::class));
    }
}
