<?php

namespace App\Factory;

use App\Entity\Driver;
use App\Repository\DriverRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Driver>
 *
 * @method        Driver|Proxy                     create(array|callable $attributes = [])
 * @method static Driver|Proxy                     createOne(array $attributes = [])
 * @method static Driver|Proxy                     find(object|array|mixed $criteria)
 * @method static Driver|Proxy                     findOrCreate(array $attributes)
 * @method static Driver|Proxy                     first(string $sortedField = 'id')
 * @method static Driver|Proxy                     last(string $sortedField = 'id')
 * @method static Driver|Proxy                     random(array $attributes = [])
 * @method static Driver|Proxy                     randomOrCreate(array $attributes = [])
 * @method static DriverRepository|RepositoryProxy repository()
 * @method static Driver[]|Proxy[]                 all()
 * @method static Driver[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Driver[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Driver[]|Proxy[]                 findBy(array $attributes)
 * @method static Driver[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Driver[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class DriverFactory extends ModelFactory
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
            'card' => self::faker()->creditCardNumber(),
            'company' => CompanyFactory::randomOrCreate(),
            'createdAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'email' => self::faker()->email(),
            'firstname' => self::faker()->firstName(),
            'isAvailable' => self::faker()->boolean(),
            'lastname' => self::faker()->lastName(),
            'licenceExpiration' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'licenceNumber' => self::faker()->creditCardNumber(),
            'password' => self::faker()->password(),
            'roles' => [],
            'updatedAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Driver $driver): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Driver::class;
    }
}
