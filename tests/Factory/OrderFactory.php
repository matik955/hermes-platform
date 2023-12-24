<?php

namespace App\Tests\Factory;

use App\Core\Order\Entity\Order;
use Doctrine\ORM\EntityRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Order>
 *
 * @method        Order|Proxy                      create(array|callable $attributes = [])
 * @method static Order|Proxy                      createOne(array $attributes = [])
 * @method static Order|Proxy                      find(object|array|mixed $criteria)
 * @method static Order|Proxy                      findOrCreate(array $attributes)
 * @method static Order|Proxy                      first(string $sortedField = 'id')
 * @method static Order|Proxy                      last(string $sortedField = 'id')
 * @method static Order|Proxy                      random(array $attributes = [])
 * @method static Order|Proxy                      randomOrCreate(array $attributes = [])
 * @method static EntityRepository|RepositoryProxy repository()
 * @method static Order[]|Proxy[]                  all()
 * @method static Order[]|Proxy[]                  createMany(int $number, array|callable $attributes = [])
 * @method static Order[]|Proxy[]                  createSequence(iterable|callable $sequence)
 * @method static Order[]|Proxy[]                  findBy(array $attributes)
 * @method static Order[]|Proxy[]                  randomRange(int $min, int $max, array $attributes = [])
 * @method static Order[]|Proxy[]                  randomSet(int $number, array $attributes = [])
 */
final class OrderFactory extends ModelFactory
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
            'comment' => self::faker()->text(),
            'createdAt' => self::faker()->dateTime(),
            'createdAtMt' => self::faker()->dateTime(),
            'expirationMt' => self::faker()->dateTime(),
            'positionId' => self::faker()->randomNumber(),
            'price' => self::faker()->randomFloat(),
            'stopLoss' => self::faker()->randomFloat(),
            'takeProfit' => self::faker()->randomFloat(),
            'tradeSymbol' => self::faker()->text(),
            'type' => self::faker()->text(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Order $order): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Order::class;
    }
}
