<?php

namespace App\Front\Account\ApiResource;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use App\Core\Account\Entity\Account;
use App\Front\Account\State\Processor\AccountProcessor;
use App\Front\Account\State\Provider\AccountProvider;
use App\Front\User\ApiResource\UserResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use App\Core\Account\Validator as AccountAssert;

#[ApiResource(
    shortName: 'Account',
    operations: [
        new Get(
            uriTemplate: '/front/accounts/{id}',
            name: 'front_get_single_account'
        ),
        new GetCollection(
            uriTemplate: '/front/accounts',
            name: 'front_get_account_collection'
        ),
        new Post(
            uriTemplate: '/front/accounts',
            name: 'front_add_user_account',
            processor: AccountProcessor::class
        ),
    ],
    normalizationContext: [
        AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
//        AbstractNormalizer::GROUPS => ['Account:read'],
    ],
    paginationItemsPerPage: 10,
    provider: AccountProvider::class,
    processor: AccountProcessor::class,
    stateOptions: new Options(entityClass: Account::class),
)]
#[AccountAssert\UniqueAccount]
class AccountResource
{
    #[ApiProperty(writable: false, identifier: true)]
    private ?int $id = null;

    #[Groups(groups: ['Account:read'])]
    private ?string $login = null;

    private ?string $password = null;

    #[Groups(groups: ['Account:read'])]
    private string $tradeServer;

    private int $mtVersion;

    private ?float $balance = null;

    private ?UserResource $user;

    /**
     * @var array<int, CopyDefinitionResource>
     */
    #[ApiProperty(writable: false)]
    public array $sourceDefinitions = [];

    public function __construct(
        ?string       $login,
        string        $password,
        string        $tradeServer,
        int           $mtVersion,
        ?UserResource $user = null
    )
    {
        $this->login = $login;
        $this->password = $password;
        $this->tradeServer = $tradeServer;
        $this->mtVersion = $mtVersion;
        $this->user = $user;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getTradeServer(): string
    {
        return $this->tradeServer;
    }

    public function getMtVersion(): int
    {
        return $this->mtVersion;
    }

    public function getBalance(): ?float
    {
        return $this->balance;
    }

    public function getUser(): ?UserResource
    {
        return $this->user;
    }

    public function getSourceDefinitions(): array
    {
        return $this->sourceDefinitions;
    }

    public function setSourceDefinitions(array $sourceDefinitions): void
    {
        $this->sourceDefinitions = $sourceDefinitions;
    }
}
