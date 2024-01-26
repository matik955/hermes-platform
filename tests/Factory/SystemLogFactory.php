<?php

namespace App\Tests\Factory;

use App\Core\System\Entity\SystemLog;
use App\Core\System\Repository\SystemLogRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<SystemLog>
 *
 * @method        SystemLog|Proxy                     create(array|callable $attributes = [])
 * @method static SystemLog|Proxy                     createOne(array $attributes = [])
 * @method static SystemLog|Proxy                     find(object|array|mixed $criteria)
 * @method static SystemLog|Proxy                     findOrCreate(array $attributes)
 * @method static SystemLog|Proxy                     first(string $sortedField = 'id')
 * @method static SystemLog|Proxy                     last(string $sortedField = 'id')
 * @method static SystemLog|Proxy                     random(array $attributes = [])
 * @method static SystemLog|Proxy                     randomOrCreate(array $attributes = [])
 * @method static SystemLogRepository|RepositoryProxy repository()
 * @method static SystemLog[]|Proxy[]                 all()
 * @method static SystemLog[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static SystemLog[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static SystemLog[]|Proxy[]                 findBy(array $attributes)
 * @method static SystemLog[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static SystemLog[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class SystemLogFactory extends ModelFactory
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
        return SystemLog::class;
    }
}
