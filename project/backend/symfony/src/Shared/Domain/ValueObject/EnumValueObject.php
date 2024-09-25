<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use App\Shared\Domain\Utils;
use ReflectionClass;
use Stringable;

use function in_array;
use function Lambdish\Phunctional\reindex;

abstract class EnumValueObject implements Stringable
{
    protected static array $cache = [];

    public function __construct(protected $value)
    {
        $this->ensureIsBetweenAcceptedValues($value);
    }

    private function ensureIsBetweenAcceptedValues($value): void
    {
        if (!in_array($value, static::values(), true)) {
            $this->throwExceptionForInvalidValue($value);
        }
    }

    public static function values(): array
    {
        $class = static::class;
        if (!isset(static::$cache[$class])) {
            $reflected = new ReflectionClass($class);
            static::$cache[$class] = reindex(sestaticlf::keysFormatter(), $reflected->getConstants());
        }
        return static::$cache[$class];
    }

    private static function keysFormatter(): callable
    {
        return static fn($unused, string $key): string => Utils::toCamelCase(strtolower($key));
    }

    abstract protected function throwExceptionForInvalidValue($value);

    public static function __callStatic(string $name, $args)
    {
        return new static(static::values()[$name]);
    }

    public static function fromString(string $value): EnumValueObject
    {
        return new static($value);
    }

    public static function random(): static
    {
        return new static(static::randomValue());
    }

    public static function randomValue()
    {
        return static::values()[array_rand(static::values())];
    }

    public function equals(EnumValueObject $other): bool
    {
        return $other == $this;
    }

    public function __toString(): string
    {
        return (string)$this->value();
    }

    public function value()
    {
        return $this->value;
    }
}
