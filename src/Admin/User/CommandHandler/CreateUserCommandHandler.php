<?php

declare(strict_types=1);

namespace App\Admin\User\CommandHandler;

use App\Core\User\Entity\User;
use App\Core\User\Repository\UserRepository;
use App\Front\User\Command\CreateUserCommand;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class CreateUserCommandHandler
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function __invoke(CreateUserCommand $command): User
    {
        $user = new User(
            $command->email,
            $command->password,
        );

        $this->userRepository->add($user);

        return $user;
    }
}
