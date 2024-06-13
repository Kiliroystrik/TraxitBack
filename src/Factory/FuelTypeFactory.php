<?php

namespace App\Factory;

use App\Entity\FuelType;
use App\Repository\FuelTypeRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<FuelType>
 *
 * @method        FuelType|Proxy                     create(array|callable $attributes = [])
 * @method static FuelType|Proxy                     createOne(array $attributes = [])
 * @method static FuelType|Proxy                     find(object|array|mixed $criteria)
 * @method static FuelType|Proxy                     findOrCreate(array $attributes)
 * @method static FuelType|Proxy                     first(string $sortedField = 'id')
 * @method static FuelType|Proxy                     last(string $sortedField = 'id')
 * @method static FuelType|Proxy                     random(array $attributes = [])
 * @method static FuelType|Proxy                     randomOrCreate(array $attributes = [])
 * @method static FuelTypeRepository|RepositoryProxy repository()
 * @method static FuelType[]|Proxy[]                 all()
 * @method static FuelType[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static FuelType[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static FuelType[]|Proxy[]                 findBy(array $attributes)
 * @method static FuelType[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static FuelType[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class FuelTypeFactory extends ModelFactory
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
            // ->afterInstantiate(function(FuelType $fuelType): void {})
        ;
    }

    protected static function getClass(): string
    {
        return FuelType::class;
    }
}
