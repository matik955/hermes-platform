<?php

namespace App\Core\Account\Repository;

use ApiPlatform\Doctrine\Orm\Paginator;
use App\Core\Account\Entity\Account;
use App\Core\Doctrine\ORM\EntityRepository;
use App\Front\Account\ApiResource\AccountResource;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;

class AccountRepository extends EntityRepository
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, $entityManager->getClassMetadata(Account::class));
    }

    public function getAccountsForUser(
        int $userId,
        int $page,
        int $itemsPerPage = 10,
        array $filters = [],
    ): Paginator
    {
        $order = [];
        $firstResult = ($page - 1) * $itemsPerPage;

        $queryBuilder = $this->createQueryBuilder('a')
            ->andWhere('a.user = :user')
            ->setParameter('user', $userId);

        if (array_key_exists('order', $filters)) {
            $order = $filters['order'];
            unset($filters['order']);
        }

        foreach ($filters as $filter => $value) {
            if ('phrase' === $filter) {
                $queryBuilder->andWhere(
                    $queryBuilder->expr()->orX(
                        $queryBuilder->expr()->like('a.login', $queryBuilder->expr()->literal('%' . $value . '%')),
                        $queryBuilder->expr()->like('a.tradeServer', $queryBuilder->expr()->literal('%' . $value . '%')),
                    )
                );

                continue;
            }

            if (!property_exists(AccountResource::class, $filter)) {
                continue;
            }

            $queryBuilder->andWhere($queryBuilder->expr()->like('a.' . $filter, $queryBuilder->expr()->literal('%' . $value . '%')));
        }

        foreach ($order as $filter => $value) {
            if (!property_exists(AccountResource::class, $filter)) {
                continue;
            }

            $queryBuilder->addOrderBy('a.' . $filter, $value);
        }

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
