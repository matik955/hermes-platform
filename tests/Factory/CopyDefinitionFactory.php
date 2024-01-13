<?php

namespace App\Tests\Factory;

use App\Core\Account\Entity\CopyDefinition;
use App\Core\Account\Repository\CopyDefinitionRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<CopyDefinition>
 *
 * @method        CopyDefinition|Proxy                     create(array|callable $attributes = [])
 * @method static CopyDefinition|Proxy                     createOne(array $attributes = [])
 * @method static CopyDefinition|Proxy                     find(object|array|mixed $criteria)
 * @method static CopyDefinition|Proxy                     findOrCreate(array $attributes)
 * @method static CopyDefinition|Proxy                     first(string $sortedField = 'id')
 * @method static CopyDefinition|Proxy                     last(string $sortedField = 'id')
 * @method static CopyDefinition|Proxy                     random(array $attributes = [])
 * @method static CopyDefinition|Proxy                     randomOrCreate(array $attributes = [])
 * @method static CopyDefinitionRepository|RepositoryProxy repository()
 * @method static CopyDefinition[]|Proxy[]                 all()
 * @method static CopyDefinition[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static CopyDefinition[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static CopyDefinition[]|Proxy[]                 findBy(array $attributes)
 * @method static CopyDefinition[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static CopyDefinition[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class CopyDefinitionFactory extends ModelFactory
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
        return [];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(CopyDefinition $copyDefinition): void {})
        ;
    }

    protected static function getClass(): string
    {
        return CopyDefinition::class;
    }
}
