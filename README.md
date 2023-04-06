# Option

[![Compliance](https://github.com/ghostwriter/option/actions/workflows/compliance.yml/badge.svg)](https://github.com/ghostwriter/option/actions/workflows/compliance.yml)
[![Supported PHP Version](https://badgen.net/packagist/php/ghostwriter/option?color=8892bf&cache=600&)](https://www.php.net/supported-versions)
[![Code Coverage](https://codecov.io/gh/ghostwriter/option/branch/main/graph/badge.svg?token=DGVCESDZCN)](https://codecov.io/gh/ghostwriter/option)
[![Type Coverage](https://shepherd.dev/github/ghostwriter/option/coverage.svg)](https://shepherd.dev/github/ghostwriter/option)
[![Latest Version on Packagist](https://badgen.net/packagist/v/ghostwriter/option?cache=600)](https://packagist.org/packages/ghostwriter/option)
[![Downloads](https://badgen.net/packagist/dt/ghostwriter/option?cache=600&color=blue)](https://packagist.org/packages/ghostwriter/option)

Provides an **`Option`** type implementation for PHP.

## Installation

You can install the package via composer:

``` bash
composer require ghostwriter/option
```

## Usage

```php
use Ghostwriter\Option\Exception\NullPointerException;
use Ghostwriter\Option\None;
use Ghostwriter\Option\Some;

$greeting = Some::create('Hello World!');
echo $greeting->unwrap();        // Hello World!

$name = None::create();
echo $name->unwrap();            // throw `NullPointerException`
echo $name->unwrapOr('Default Value'); // Default Value

None::create();      // return `None`
Some::of(null);      // return `None`
Some::create(null);  // throw `NullPointerException`

--- Example

function divide(int $x, int $y): OptionInterface
{
    if ($y === 0) {
        return None::create();
    }

    return Some::create($x / $y);
}

divide(1, 0); // None
divide(1, 1); // Some(1)
```

## API

### `SomeInterface`

``` php
/**
 * @immutable
 *
 * @implements OptionInterface<TValue>
 *
 * @template TValue
 */
interface SomeInterface extends OptionInterface
{
    /**
     * @template TSomeValue
     *
     * @param TSomeValue $value
     *
     * @return self<TSomeValue>
     */
    public static function create(mixed $value): self;
}
```

### `NoneInterface`

``` php
/**
 * @immutable
 *
 * @implements OptionInterface<TValue>
 *
 * @template TValue
 */
interface NoneInterface extends OptionInterface
{
    /** @return self<TValue> */
    public static function create(): self;
}
```

### `OptionInterface`

``` php
/**
 * @implements IteratorAggregate<TValue>
 *
 * @template TValue
 */
interface OptionInterface extends IteratorAggregate
{
    /**
     * Returns None if the Option is None, otherwise returns $option.
     */
    public function and(self $option): self;

    /**
     * Returns None if the option is None, otherwise calls $function with the wrapped value and returns the result.
     *
     * @template TAndThen
     *
     * @param callable(TValue):self $function
     *
     * @return self<TAndThen|TValue>
     */
    public function andThen(callable $function): self;

    /**
     * Returns true if the option is a Some value containing the given $value.
     *
     * @param TValue $value
     */
    public function contains(mixed $value): bool;

    /**
     * Returns the contained Some value, consuming the self value.
     *
     * @throws Throwable if the value is a None with a custom $throwable provided
     *
     * @return TValue
     */
    public function expect(Throwable $throwable): mixed;

    /**
     * Returns None if the option is None, otherwise calls $function with the wrapped value and returns: Some(TValue) if
     * $function returns true (where TValue is the wrapped value), and None if $function returns false.
     *
     * @param callable(TValue):bool $function
     *
     * @return self<TValue>
     */
    public function filter(callable $function): self;

    /**
     * Converts from Option<Option<TValue>> to Option<TValue>. Flattening only removes one level of nesting at a time.
     *
     * @return self<TValue>
     */
    public function flatten(): self;

    public function getIterator(): Traversable;

    /**
     * Returns true if the Option is an instance of None.
     */
    public function isNone(): bool;

    /**
     * Returns true if the Option is an instance of Some.
     */
    public function isSome(): bool;

    /**
     * Maps a Some<TValue> to Some<TValue> by applying the callable $function to the contained value.
     *
     * @template TMap
     *
     * @param callable(TValue):TMap $function
     *
     * @return self<TMap>
     */
    public function map(callable $function): self;

    /**
     * Applies a function to the contained value (if any), or returns the provided default (if not).
     *
     * @template TFunction
     * @template TFallback
     *
     * @param callable(TValue): TFunction $function
     * @param TFallback                   $fallback
     *
     * @return TFallback|TFunction
     */
    public function mapOr(callable $function, mixed $fallback): mixed;

    /**
     * Applies a function to the contained value (if any), or computes a default (if not).
     *
     * @template TFunction
     * @template TFallback
     *
     * @param callable(TValue):TFunction $function
     * @param callable():TFallback       $fallback
     *
     * @return TFallback|TFunction
     */
    public function mapOrElse(callable $function, callable $fallback): mixed;

    /**
     * Creates an option with the given value.
     *
     * By default, we treat null as the None case, and everything else as Some.
     *
     * @template TNullableValue
     *
     * @param TNullableValue $value the actual value
     *
     * @return self<TNullableValue|TValue>
     */
    public static function of(mixed $value): self;

    /**
     * Returns the option if it contains a value, otherwise returns $option.
     *
     * Arguments passed to or are eagerly evaluated; if you are passing the result of a function call, it is recommended
     * to use orElse, which is lazily evaluated.
     */
    public function or(self $option): self;

    /**
     * Returns the option if it contains a value, otherwise calls $function and returns the result.
     *
     * @template TCallableResultValue
     *
     * @param callable(): OptionInterface<TCallableResultValue> $function
     */
    public function orElse(callable $function): self;

    /**
     * Returns the value out of the option<TValue> if it is Some(TValue).
     *
     * @throws NullPointerException if the Option<TValue> is None
     *
     * @return TValue
     */
    public function unwrap(): mixed;

    /**
     * Returns the contained value or a default $fallback value.
     *
     * @template TFallbackValue
     *
     * @param TFallbackValue $fallback
     *
     * @return TFallbackValue|TValue
     */
    public function unwrapOr(mixed $fallback): mixed;

    /**
     * Returns the contained value or computes it from the given $function.
     *
     * @template TCallableResultValue
     *
     * @param callable():TCallableResultValue $function
     *
     * @return TCallableResultValue|TValue
     */
    public function unwrapOrElse(callable $function): mixed;
}
```

## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG.md](./CHANGELOG.md) for more information what has changed recently.

## Security

If you discover any security related issues, please email `nathanael.esayeas@protonmail.com` instead of using the issue tracker.

## Sponsors

[[`Become a GitHub Sponsor`](https://github.com/sponsors/ghostwriter)]

## Credits

- [Nathanael Esayeas](https://github.com/ghostwriter)
- [All Contributors](https://github.com/ghostwriter/option/contributors)

## License

The BSD-3-Clause. Please see [License File](./LICENSE) for more information.
