<?php

namespace App\Factory;

use App\Entity\TourStep;
use App\Repository\TourStepRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<TourStep>
 *
 * @method        TourStep|Proxy                     create(array|callable $attributes = [])
 * @method static TourStep|Proxy                     createOne(array $attributes = [])
 * @method static TourStep|Proxy                     find(object|array|mixed $criteria)
 * @method static TourStep|Proxy                     findOrCreate(array $attributes)
 * @method static TourStep|Proxy                     first(string $sortedField = 'id')
 * @method static TourStep|Proxy                     last(string $sortedField = 'id')
 * @method static TourStep|Proxy                     random(array $attributes = [])
 * @method static TourStep|Proxy                     randomOrCreate(array $attributes = [])
 * @method static TourStepRepository|RepositoryProxy repository()
 * @method static TourStep[]|Proxy[]                 all()
 * @method static TourStep[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static TourStep[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static TourStep[]|Proxy[]                 findBy(array $attributes)
 * @method static TourStep[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static TourStep[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class TourStepFactory extends ModelFactory
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
            'actualArrival' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'actualDate' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'actualDeparture' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'createdAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'stepNumber' => self::faker()->randomNumber(),
            'tour' => TourFactory::new(),
            'updatedAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(TourStep $tourStep): void {})
        ;
    }

    protected static function getClass(): string
    {
        return TourStep::class;
    }
}
