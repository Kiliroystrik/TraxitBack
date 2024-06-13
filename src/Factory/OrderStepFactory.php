<?php

namespace App\Factory;

use App\Entity\OrderStep;
use App\Repository\OrderStepRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<OrderStep>
 *
 * @method        OrderStep|Proxy                     create(array|callable $attributes = [])
 * @method static OrderStep|Proxy                     createOne(array $attributes = [])
 * @method static OrderStep|Proxy                     find(object|array|mixed $criteria)
 * @method static OrderStep|Proxy                     findOrCreate(array $attributes)
 * @method static OrderStep|Proxy                     first(string $sortedField = 'id')
 * @method static OrderStep|Proxy                     last(string $sortedField = 'id')
 * @method static OrderStep|Proxy                     random(array $attributes = [])
 * @method static OrderStep|Proxy                     randomOrCreate(array $attributes = [])
 * @method static OrderStepRepository|RepositoryProxy repository()
 * @method static OrderStep[]|Proxy[]                 all()
 * @method static OrderStep[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static OrderStep[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static OrderStep[]|Proxy[]                 findBy(array $attributes)
 * @method static OrderStep[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static OrderStep[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class OrderStepFactory extends ModelFactory
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
            '_order' => OrderFactory::randomOrCreate(),
            'address' => AddressFactory::createOne(['company' => CompanyFactory::randomOrCreate()]),
            'createdAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'description' => self::faker()->text(),
            'product' => ProductFactory::randomOrCreate(),
            'quantity' => self::faker()->randomFloat(null, 0, 1000),
            'scheduledArrival' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'scheduledDeparture' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'status' => StatusFactory::randomOrCreate(),
            'type' => self::faker()->word(),
            'unit' => UnitFactory::randomOrCreate(),
            'updatedAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(OrderStep $orderStep): void {})
        ;
    }

    protected static function getClass(): string
    {
        return OrderStep::class;
    }
}
