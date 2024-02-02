<?php

namespace App\Admin\Account\ApiResource;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Admin\Account\Payload\AccountBalanceUpdatePayload;
use App\Admin\Account\State\Processor\AccountBalanceUpdateProcessor;
use App\Core\Account\Entity\Account;
use App\Admin\Account\State\Processor\AccountProcessor;
use App\Admin\Account\State\Provider\AccountProvider;
use App\Admin\User\ApiResource\UserResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use App\Core\Account\Validator as AccountAssert;

#[ApiResource(
    shortName: 'Account',
    operations: [
        new Get(
            uriTemplate: '/admin/accounts/{id}',
            name: 'admin_get_single_account'
        ),
        new GetCollection(
            uriTemplate: '/admin/accounts',
            name: 'admin_get_account_collection'
        ),
        new Delete(
            uriTemplate: '/admin/accounts/{id}',
            name: 'admin_remove_user_account',
        ),
        new Patch(
            '/admin/accounts/{id}/balance',
            input: AccountBalanceUpdatePayload::class,
            output: AccountBalanceUpdatePayload::class,
            name: 'admin_update_account_balance',
            processor: AccountBalanceUpdateProcessor::class
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
#[ApiResource(
    uriTemplate: '/admin/user/{userId}/accounts',
    shortName: 'Account',
    operations: [
        new GetCollection(
            name: 'admin_get_user_accounts',
            provider: AccountProvider::class
        ),
        new Post(
            read: false,
            name: 'admin_add_user_account',
            processor: AccountProcessor::class
        ),
    ],
    uriVariables: [
        'userId' => new Link(toProperty: 'user', fromClass: UserResource::class),
    ],
    paginationItemsPerPage: 10,
    stateOptions: new Options(entityClass: Account::class)
)]
#[ApiFilter(SearchFilter::class, properties: [
    'login' => 'partial',
    'tradeServer' => 'partial',
    'mtVersion' => 'exact'
])]
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
        ?float         $balance = null,
        ?UserResource $user = null
    )
    {
        $this->login = $login;
        $this->password = $password;
        $this->tradeServer = $tradeServer;
        $this->mtVersion = $mtVersion;
        $this->balance = $balance;
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
