<?php

namespace App\Factory;

use App\Entity\VehicleModel;
use App\Repository\VehicleModelRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<VehicleModel>
 *
 * @method        VehicleModel|Proxy                     create(array|callable $attributes = [])
 * @method static VehicleModel|Proxy                     createOne(array $attributes = [])
 * @method static VehicleModel|Proxy                     find(object|array|mixed $criteria)
 * @method static VehicleModel|Proxy                     findOrCreate(array $attributes)
 * @method static VehicleModel|Proxy                     first(string $sortedField = 'id')
 * @method static VehicleModel|Proxy                     last(string $sortedField = 'id')
 * @method static VehicleModel|Proxy                     random(array $attributes = [])
 * @method static VehicleModel|Proxy                     randomOrCreate(array $attributes = [])
 * @method static VehicleModelRepository|RepositoryProxy repository()
 * @method static VehicleModel[]|Proxy[]                 all()
 * @method static VehicleModel[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static VehicleModel[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static VehicleModel[]|Proxy[]                 findBy(array $attributes)
 * @method static VehicleModel[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static VehicleModel[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class VehicleModelFactory extends ModelFactory
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
            'brand' => BrandFactory::randomOrCreate(),
            'name' => self::faker()->word(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(VehicleModel $vehicleModel): void {})
        ;
    }

    protected static function getClass(): string
    {
        return VehicleModel::class;
    }
}
