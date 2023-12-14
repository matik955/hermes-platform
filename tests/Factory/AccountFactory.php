<?php

namespace App\Tests\Factory;

use App\Core\Account\Entity\Account;
use App\Core\Account\Repository\AccountRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Account>
 *
 * @method        Account|Proxy                     create(array|callable $attributes = [])
 * @method static Account|Proxy                     createOne(array $attributes = [])
 * @method static Account|Proxy                     find(object|array|mixed $criteria)
 * @method static Account|Proxy                     findOrCreate(array $attributes)
 * @method static Account|Proxy                     first(string $sortedField = 'id')
 * @method static Account|Proxy                     last(string $sortedField = 'id')
 * @method static Account|Proxy                     random(array $attributes = [])
 * @method static Account|Proxy                     randomOrCreate(array $attributes = [])
 * @method static AccountRepository|RepositoryProxy repository()
 * @method static Account[]|Proxy[]                 all()
 * @method static Account[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Account[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Account[]|Proxy[]                 findBy(array $attributes)
 * @method static Account[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Account[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class AccountFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        return [
            'login' => self::faker()->text(180),
            'mtVersion' => self::faker()->randomNumber(),
            'password' => self::faker()->text(),
            'tradeServer' => self::faker()->text(),
            'balance' => self::faker()->randomFloat(),
            'created_at' => self::faker()->dateTime
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Account $account): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Account::class;
    }
}
