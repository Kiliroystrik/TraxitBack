<?php

namespace App\Factory;

use App\Entity\TourType;
use App\Repository\TourTypeRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<TourType>
 *
 * @method        TourType|Proxy                     create(array|callable $attributes = [])
 * @method static TourType|Proxy                     createOne(array $attributes = [])
 * @method static TourType|Proxy                     find(object|array|mixed $criteria)
 * @method static TourType|Proxy                     findOrCreate(array $attributes)
 * @method static TourType|Proxy                     first(string $sortedField = 'id')
 * @method static TourType|Proxy                     last(string $sortedField = 'id')
 * @method static TourType|Proxy                     random(array $attributes = [])
 * @method static TourType|Proxy                     randomOrCreate(array $attributes = [])
 * @method static TourTypeRepository|RepositoryProxy repository()
 * @method static TourType[]|Proxy[]                 all()
 * @method static TourType[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static TourType[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static TourType[]|Proxy[]                 findBy(array $attributes)
 * @method static TourType[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static TourType[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class TourTypeFactory extends ModelFactory
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
            'description' => self::faker()->text(),
            'name' => self::faker()->word(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(TourType $tourType): void {})
        ;
    }

    protected static function getClass(): string
    {
        return TourType::class;
    }
}
