<?php

namespace App\Core\Resource\Repository;

use App\Core\Resource\Model\ResourceInterface;
use Doctrine\Persistence\ObjectRepository;

interface RepositoryInterface extends ObjectRepository
{
    public function add(ResourceInterface $resource): void;

    public function remove(ResourceInterface $resource): void;

    public function flush(): void;
}
