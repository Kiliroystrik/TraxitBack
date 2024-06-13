<?php

namespace App\Factory;

use App\Entity\Unit;
use App\Repository\UnitRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Unit>
 *
 * @method        Unit|Proxy                     create(array|callable $attributes = [])
 * @method static Unit|Proxy                     createOne(array $attributes = [])
 * @method static Unit|Proxy                     find(object|array|mixed $criteria)
 * @method static Unit|Proxy                     findOrCreate(array $attributes)
 * @method static Unit|Proxy                     first(string $sortedField = 'id')
 * @method static Unit|Proxy                     last(string $sortedField = 'id')
 * @method static Unit|Proxy                     random(array $attributes = [])
 * @method static Unit|Proxy                     randomOrCreate(array $attributes = [])
 * @method static UnitRepository|RepositoryProxy repository()
 * @method static Unit[]|Proxy[]                 all()
 * @method static Unit[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Unit[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Unit[]|Proxy[]                 findBy(array $attributes)
 * @method static Unit[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Unit[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class UnitFactory extends ModelFactory
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
            'name' => self::faker()->word(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Unit $unit): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Unit::class;
    }
}
