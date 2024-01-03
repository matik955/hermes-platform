<?php

namespace App\Front\User\Provider;

use App\Core\User\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

readonly class FrontUserProvider
{
    public function __construct(
        private TokenStorageInterface $tokenStorage)
    {
    }

    public function getUser(): ?User
    {
        if (null === $token = $this->tokenStorage->getToken()) {
            return null;
        }

        $user = $token->getUser();

        if (
            $user instanceof User
        ) {
            return $user;
        }

        return null;
    }
}
