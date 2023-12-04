<?php

namespace App\Core\Doctrine\ORM;

use App\Core\Resource\Repository\RepositoryInterface;
use Doctrine\ORM\EntityRepository as BaseEntityRepository;

class EntityRepository extends BaseEntityRepository implements RepositoryInterface
{
    use ResourceRepositoryTrait;
}
