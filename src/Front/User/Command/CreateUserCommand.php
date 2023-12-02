<?php

declare(strict_types=1);

namespace App\Front\User\Command;

final readonly class CreateUserCommand
{
    public function __construct(
        public string $email,
        public array $roles,
        public string $password
    )
    {
    }
}
