<?php

namespace App\Core\Account\Repository;

use ApiPlatform\Doctrine\Orm\Paginator;
use App\Core\Account\Entity\CopyDefinition;
use App\Core\Doctrine\ORM\EntityRepository;
use App\Front\Account\ApiResource\CopyDefinitionResource;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;

class CopyDefinitionRepository extends EntityRepository
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, $entityManager->getClassMetadata(CopyDefinition::class));
    }

    public function getPaginatorForUser(
        int $userId,
        int $page,
        int $itemsPerPage = 10,
        array $filters = []
    ): Paginator
    {
        $firstResult = ($page - 1) * $itemsPerPage;

        $queryBuilder = $this->createQueryBuilder('c')
            ->innerJoin('c.sourceAccount', 'a')
            ->andWhere('a.user = :user')
            ->setParameter('user', $userId);

        foreach ($filters as $filter => $value) {
            if (!property_exists(CopyDefinitionResource::class, $filter)) {
                continue;
            }

            $queryBuilder->andWhere($queryBuilder->expr()->like('a.' . $filter, $queryBuilder->expr()->literal('%' . $value . '%')));
        }

        $criteria = Criteria::create()
            ->setFirstResult($firstResult)
            ->setMaxResults($itemsPerPage);
        $queryBuilder->addCriteria($criteria);
        $queryBuilder->setMaxResults($itemsPerPage);

        $doctrinePaginator = new DoctrinePaginator($queryBuilder);
        return new Paginator($doctrinePaginator);
    }
}
