<?php

namespace App\Factory;

use App\Entity\Tour;
use App\Repository\TourRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Tour>
 *
 * @method        Tour|Proxy                     create(array|callable $attributes = [])
 * @method static Tour|Proxy                     createOne(array $attributes = [])
 * @method static Tour|Proxy                     find(object|array|mixed $criteria)
 * @method static Tour|Proxy                     findOrCreate(array $attributes)
 * @method static Tour|Proxy                     first(string $sortedField = 'id')
 * @method static Tour|Proxy                     last(string $sortedField = 'id')
 * @method static Tour|Proxy                     random(array $attributes = [])
 * @method static Tour|Proxy                     randomOrCreate(array $attributes = [])
 * @method static TourRepository|RepositoryProxy repository()
 * @method static Tour[]|Proxy[]                 all()
 * @method static Tour[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Tour[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Tour[]|Proxy[]                 findBy(array $attributes)
 * @method static Tour[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Tour[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class TourFactory extends ModelFactory
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
            'createdAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'endDate' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'startDate' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'updatedAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Tour $tour): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Tour::class;
    }
}
