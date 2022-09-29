# Option

[![Compliance](https://github.com/ghostwriter/option/actions/workflows/compliance.yml/badge.svg)](https://github.com/ghostwriter/option/actions/workflows/compliance.yml)
[![Supported PHP Version](https://badgen.net/packagist/php/ghostwriter/option?color=8892bf&cache=600&)](https://www.php.net/supported-versions)
[![Type Coverage](https://shepherd.dev/github/ghostwriter/option/coverage.svg)](https://shepherd.dev/github/ghostwriter/option)
[![Latest Version on Packagist](https://badgen.net/packagist/v/ghostwriter/option?cache=600)](https://packagist.org/packages/ghostwriter/option)
[![Downloads](https://badgen.net/packagist/dt/ghostwriter/option?cache=600&color=blue)](https://packagist.org/packages/ghostwriter/option)

Provides an `Option` type implementation for PHP.

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
        return Nome::create();
    }

    return Some::create($x / $y);
}

divide(1, 0); // None
divide(1, 1); // Some(1)
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
