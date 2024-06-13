<?php

namespace App\Factory;

use App\Entity\Brand;
use App\Repository\BrandRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Brand>
 *
 * @method        Brand|Proxy                     create(array|callable $attributes = [])
 * @method static Brand|Proxy                     createOne(array $attributes = [])
 * @method static Brand|Proxy                     find(object|array|mixed $criteria)
 * @method static Brand|Proxy                     findOrCreate(array $attributes)
 * @method static Brand|Proxy                     first(string $sortedField = 'id')
 * @method static Brand|Proxy                     last(string $sortedField = 'id')
 * @method static Brand|Proxy                     random(array $attributes = [])
 * @method static Brand|Proxy                     randomOrCreate(array $attributes = [])
 * @method static BrandRepository|RepositoryProxy repository()
 * @method static Brand[]|Proxy[]                 all()
 * @method static Brand[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Brand[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Brand[]|Proxy[]                 findBy(array $attributes)
 * @method static Brand[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Brand[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class BrandFactory extends ModelFactory
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
        // On ne peut pas avoir deux marques qui ont le même nom dans une meme instanciation
        return [
            'company' => CompanyFactory::randomOrCreate(),
            'name' => self::faker()->text(50),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Brand $brand): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Brand::class;
    }
}
