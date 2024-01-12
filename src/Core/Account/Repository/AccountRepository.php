<?php

namespace App\Core\Account\Repository;

use ApiPlatform\Doctrine\Orm\Paginator;
use App\Core\Account\Entity\Account;
use App\Core\Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;

class AccountRepository extends EntityRepository
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, $entityManager->getClassMetadata(Account::class));
    }

    public function getAccountsForUser(int $userId, int $page, int $itemsPerPage = 10): Paginator
    {
        $firstResult = ($page - 1) * $itemsPerPage;

        $queryBuilder = $this->createQueryBuilder('a')
            ->andWhere('a.user = :user')
            ->setParameter('user', $userId);

        $criteria = Criteria::create()
            ->setFirstResult($firstResult)
            ->setMaxResults($itemsPerPage);
        $queryBuilder->addCriteria($criteria);
        $queryBuilder->setMaxResults($itemsPerPage);

        $doctrinePaginator = new DoctrinePaginator($queryBuilder);
        $paginator = new Paginator($doctrinePaginator);

        return $paginator;
    }
}
