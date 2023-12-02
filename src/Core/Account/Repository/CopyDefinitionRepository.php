<?php

namespace App\Core\Account\Repository;

use App\Core\Account\Entity\CopyDefinition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CopyDefinition>
 *
 * @method CopyDefinition|null find($id, $lockMode = null, $lockVersion = null)
 * @method CopyDefinition|null findOneBy(array $criteria, array $orderBy = null)
 * @method CopyDefinition[]    findAll()
 * @method CopyDefinition[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CopyDefinitionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CopyDefinition::class);
    }
}
