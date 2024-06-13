<?php

namespace App\Factory;

use App\Entity\Vehicle;
use App\Repository\VehicleRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Vehicle>
 *
 * @method        Vehicle|Proxy                     create(array|callable $attributes = [])
 * @method static Vehicle|Proxy                     createOne(array $attributes = [])
 * @method static Vehicle|Proxy                     find(object|array|mixed $criteria)
 * @method static Vehicle|Proxy                     findOrCreate(array $attributes)
 * @method static Vehicle|Proxy                     first(string $sortedField = 'id')
 * @method static Vehicle|Proxy                     last(string $sortedField = 'id')
 * @method static Vehicle|Proxy                     random(array $attributes = [])
 * @method static Vehicle|Proxy                     randomOrCreate(array $attributes = [])
 * @method static VehicleRepository|RepositoryProxy repository()
 * @method static Vehicle[]|Proxy[]                 all()
 * @method static Vehicle[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Vehicle[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Vehicle[]|Proxy[]                 findBy(array $attributes)
 * @method static Vehicle[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Vehicle[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class VehicleFactory extends ModelFactory
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
            'company' => CompanyFactory::randomOrCreate(),
            'isAvailable' => self::faker()->boolean(),
            'mileage' => self::faker()->randomFloat(6, 0, 10000),
            'model' => VehicleModelFactory::randomOrCreate(),
            'registeredAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'registrationNumber' => self::faker()->randomNumber(5),
            'fuelTypes' => [FuelTypeFactory::randomOrCreate()],
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Vehicle $vehicle): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Vehicle::class;
    }
}
