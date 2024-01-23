<?php

namespace App\Tests\Factory;

use App\Core\Account\Entity\AccountLog;
use App\Core\Account\Repository\AccountLogRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<AccountLog>
 *
 * @method        AccountLog|Proxy                     create(array|callable $attributes = [])
 * @method static AccountLog|Proxy                     createOne(array $attributes = [])
 * @method static AccountLog|Proxy                     find(object|array|mixed $criteria)
 * @method static AccountLog|Proxy                     findOrCreate(array $attributes)
 * @method static AccountLog|Proxy                     first(string $sortedField = 'id')
 * @method static AccountLog|Proxy                     last(string $sortedField = 'id')
 * @method static AccountLog|Proxy                     random(array $attributes = [])
 * @method static AccountLog|Proxy                     randomOrCreate(array $attributes = [])
 * @method static AccountLogRepository|RepositoryProxy repository()
 * @method static AccountLog[]|Proxy[]                 all()
 * @method static AccountLog[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static AccountLog[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static AccountLog[]|Proxy[]                 findBy(array $attributes)
 * @method static AccountLog[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static AccountLog[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class AccountLogFactory extends ModelFactory
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
            'type' => self::faker()->word(),
            'data' => [],
            'owner' => AccountFactory::createOne()
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
        return AccountLog::class;
    }
}
