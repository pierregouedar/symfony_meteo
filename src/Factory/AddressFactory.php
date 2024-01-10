<?php

namespace App\Factory;

use App\Entity\Address;
use App\Repository\AddressRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Address>
 *
 * @method        Address|Proxy create(array|callable $attributes = [])
 * @method static Address|Proxy createOne(array $attributes = [])
 * @method static Address|Proxy find(object|array|mixed $criteria)
 * @method static Address|Proxy findOrCreate(array $attributes)
 * @method static Address|Proxy first(string $sortedField = 'id')
 * @method static Address|Proxy last(string $sortedField = 'id')
 * @method static Address|Proxy random(array $attributes = [])
 * @method static Address|Proxy randomOrCreate(array $attributes = [])
 * @method static AddressRepository|RepositoryProxy repository()
 * @method static Address[]|Proxy[] all()
 * @method static Address[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Address[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static Address[]|Proxy[] findBy(array $attributes)
 * @method static Address[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Address[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class AddressFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function getDefaults(): array
    {
        return [
            'latitude' => self::faker()->randomFloat(),
            'longitude' => self::faker()->randomFloat(),
            'type' => self::faker()->text(32),
            'user' => UserFactory::new(),
        ];
    }

    protected function initialize(): self
    {
        return $this;
    }

    protected static function getClass(): string
    {
        return Address::class;
    }
}
