<?php

namespace App\Factory;

use App\Entity\VehicleAssignment;
use App\Repository\VehicleAssignmentRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<VehicleAssignment>
 *
 * @method        VehicleAssignment|Proxy                     create(array|callable $attributes = [])
 * @method static VehicleAssignment|Proxy                     createOne(array $attributes = [])
 * @method static VehicleAssignment|Proxy                     find(object|array|mixed $criteria)
 * @method static VehicleAssignment|Proxy                     findOrCreate(array $attributes)
 * @method static VehicleAssignment|Proxy                     first(string $sortedField = 'id')
 * @method static VehicleAssignment|Proxy                     last(string $sortedField = 'id')
 * @method static VehicleAssignment|Proxy                     random(array $attributes = [])
 * @method static VehicleAssignment|Proxy                     randomOrCreate(array $attributes = [])
 * @method static VehicleAssignmentRepository|RepositoryProxy repository()
 * @method static VehicleAssignment[]|Proxy[]                 all()
 * @method static VehicleAssignment[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static VehicleAssignment[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static VehicleAssignment[]|Proxy[]                 findBy(array $attributes)
 * @method static VehicleAssignment[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static VehicleAssignment[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class VehicleAssignmentFactory extends ModelFactory
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
            'endDate' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'startDate' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'tour' => TourFactory::randomOrCreate(),
            'vehicle' => VehicleFactory::randomOrCreate(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(VehicleAssignment $vehicleAssignment): void {})
        ;
    }

    protected static function getClass(): string
    {
        return VehicleAssignment::class;
    }
}
