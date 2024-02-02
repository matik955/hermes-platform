<?php

namespace App\Core\Doctrine\ORM;

use App\Core\Resource\Model\ResourceInterface;

trait ResourceRepositoryTrait
{
    public function add(ResourceInterface $resource): void
    {
        $this->_em->persist($resource);
        $this->_em->flush();
    }

    public function remove(ResourceInterface $resource): void
    {
        if (null !== $this->find($resource->getId())) {
            $this->_em->remove($resource);
            $this->_em->flush();
        }
    }

    public function flush(): void
    {
        $this->_em->flush();
    }
}
