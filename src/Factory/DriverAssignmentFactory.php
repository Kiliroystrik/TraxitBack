<?php

namespace App\Factory;

use App\Entity\DriverAssignment;
use App\Repository\DriverAssignmentRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<DriverAssignment>
 *
 * @method        DriverAssignment|Proxy                     create(array|callable $attributes = [])
 * @method static DriverAssignment|Proxy                     createOne(array $attributes = [])
 * @method static DriverAssignment|Proxy                     find(object|array|mixed $criteria)
 * @method static DriverAssignment|Proxy                     findOrCreate(array $attributes)
 * @method static DriverAssignment|Proxy                     first(string $sortedField = 'id')
 * @method static DriverAssignment|Proxy                     last(string $sortedField = 'id')
 * @method static DriverAssignment|Proxy                     random(array $attributes = [])
 * @method static DriverAssignment|Proxy                     randomOrCreate(array $attributes = [])
 * @method static DriverAssignmentRepository|RepositoryProxy repository()
 * @method static DriverAssignment[]|Proxy[]                 all()
 * @method static DriverAssignment[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static DriverAssignment[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static DriverAssignment[]|Proxy[]                 findBy(array $attributes)
 * @method static DriverAssignment[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static DriverAssignment[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class DriverAssignmentFactory extends ModelFactory
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
            'driver' => DriverFactory::randomOrCreate(),
            'endDate' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'role' => [],
            'startDate' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'tour' => TourFactory::randomOrCreate(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(DriverAssignment $driverAssignment): void {})
        ;
    }

    protected static function getClass(): string
    {
        return DriverAssignment::class;
    }
}
