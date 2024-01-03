<?php

namespace App\Front\User\ApiResource;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Core\User\Entity\User;
use App\Core\User\Interface\UserResourceInterface;
use App\Front\Account\ApiResource\AccountResource;
use App\Front\User\State\Processor\CreateUserProcessor;
use App\Front\User\State\Provider\UserProvider;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Core\User\Validator as UserAssert;

#[ApiResource(
    shortName: 'User',
    operations: [
        new Post(
            uriTemplate: '/front/users',
            name: 'front_add_user',
            processor: CreateUserProcessor::class
        ),
    ],
    provider: UserProvider::class,
    stateOptions: new Options(entityClass: User::class)
)]
#[UserAssert\UniqueUser]
class UserResource implements UserResourceInterface
{
    #[ApiProperty(readable: false, writable: false, identifier: true)]
    private ?int $id = null;

    #[Assert\NotNull]
    #[Assert\Email]
    #[Groups(groups: ['Account:read'])]
    private ?string $email;

    private array $roles;

    #[Assert\NotNull]
    private string $password;

    private ?AccountResource $account = null;


    public function __construct(
        string $email,
        string $password,
        ?array $roles = [],
    )
    {
        $this->email = $email;
        $this->roles = $roles;
        $this->password = $password;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getAccount(): ?AccountResource
    {
        return $this->account;
    }

    public function setAccount(AccountResource $account): void
    {
        $this->account = $account;
    }
}
