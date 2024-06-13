<?php

namespace App\Factory;

use App\Entity\DrivingSegment;
use App\Repository\DrivingSegmentRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<DrivingSegment>
 *
 * @method        DrivingSegment|Proxy                     create(array|callable $attributes = [])
 * @method static DrivingSegment|Proxy                     createOne(array $attributes = [])
 * @method static DrivingSegment|Proxy                     find(object|array|mixed $criteria)
 * @method static DrivingSegment|Proxy                     findOrCreate(array $attributes)
 * @method static DrivingSegment|Proxy                     first(string $sortedField = 'id')
 * @method static DrivingSegment|Proxy                     last(string $sortedField = 'id')
 * @method static DrivingSegment|Proxy                     random(array $attributes = [])
 * @method static DrivingSegment|Proxy                     randomOrCreate(array $attributes = [])
 * @method static DrivingSegmentRepository|RepositoryProxy repository()
 * @method static DrivingSegment[]|Proxy[]                 all()
 * @method static DrivingSegment[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static DrivingSegment[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static DrivingSegment[]|Proxy[]                 findBy(array $attributes)
 * @method static DrivingSegment[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static DrivingSegment[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class DrivingSegmentFactory extends ModelFactory
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
            'driverAssignment' => DriverAssignmentFactory::new(),
            'endDate' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'startDate' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'vehicleAssignment' => VehicleAssignmentFactory::new(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(DrivingSegment $drivingSegment): void {})
        ;
    }

    protected static function getClass(): string
    {
        return DrivingSegment::class;
    }
}
