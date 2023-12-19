<?php

namespace App\Front\User\ApiResource;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Core\User\Entity\User;
use App\Front\User\State\Processor\CreateUserProcessor;
use App\Front\User\State\Provider\UserProvider;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'User',
    operations: [
        new Post(processor: CreateUserProcessor::class,),
        new Get(name: 'GetSingleUser'),
        new GetCollection(name:'GetUsers'),
    ],
    provider: UserProvider::class,
    stateOptions: new Options(entityClass: User::class)
)]
class UserResource
{
    #[ApiProperty(readable: false, writable: false, identifier: true)]
    private ?int $id = null;

    #[Assert\NotNull]
    #[Assert\Email]
    #[Groups(groups: ['Account:read'])]
    private ?string $email;

    #[Assert\NotNull]
    #[Groups(groups: ['Account:read'])]
    private array $roles = [];

    #[Assert\NotNull]
    private string $password;


    public function __construct(
        string $email,
        array $roles,
        string $password
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
}
