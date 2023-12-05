<?php

namespace App\Core\Account\Repository;

use App\Core\Account\Entity\CopyDefinition;
use App\Core\Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;

class CopyDefinitionRepository extends EntityRepository
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, $entityManager->getClassMetadata(CopyDefinition::class));
    }
}
