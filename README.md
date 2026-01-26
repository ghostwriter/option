# Option

[![GitHub Sponsors](https://img.shields.io/github/sponsors/ghostwriter?label=Sponsor+@ghostwriter/option&logo=GitHub+Sponsors)](https://github.com/sponsors/ghostwriter)
[![Automation](https://github.com/ghostwriter/option/actions/workflows/automation.yml/badge.svg)](https://github.com/ghostwriter/option/actions/workflows/automation.yml)
[![Supported PHP Version](https://badgen.net/packagist/php/ghostwriter/option?color=8892bf)](https://www.php.net/supported-versions)
[![Downloads](https://badgen.net/packagist/dt/ghostwriter/option?color=blue)](https://packagist.org/packages/ghostwriter/option)

Provides an **`Option`** type implementation for PHP.

## Installation

You can install the package via composer:

``` bash
composer require ghostwriter/option
```

### Star ⭐️ this repo if you find it useful

You can also star (🌟) this repo to find it easier later.

## Usage

```php
use Ghostwriter\Option\Exception\NullPointerException;
use Ghostwriter\Option\None;
use Ghostwriter\Option\Some;
use Ghostwriter\Option\Option;

$message = '#BlackLivesMatter';
Option::new($message); // return `Some`

$some = Some::new($message); // return `Some`
echo $some->get(); // prints #BlackLivesMatter

$none = None::new(); // return `None`

// calling the `get` method on `None` will throw `NullPointerException`
echo $none->get(); // throw `NullPointerException`

echo $none->getOr('#BLM'); // prints #BLM

// calling the `new` static method with `null` will return `None`
Option::new(null); // return `None`

// calling the `new` static method with `null will throw `NullPointerException`
Some::new(null); // throws `NullPointerException`

// calling the `new` static method with `None will throw `NullPointerException`
Some::new(None::new()); // throws `NullPointerException`

--- Example

function divide(int $x, int $y): OptionInterface
{
    if ($y === 0) {
        return None::new();
    }

    return Some::new($x / $y);
}

divide(1, 0); // None
divide(1, 1); // Some(1)
```

## Testing

``` bash
composer test
```

### Credits

- [Nathanael Esayeas](https://github.com/ghostwriter)
- [All Contributors](https://github.com/ghostwriter/option/contributors)

### Changelog

Please see [CHANGELOG.md](./CHANGELOG.md) for more information on what has changed recently.

### License

Please see [LICENSE](./LICENSE) for more information on the license that applies to this project.

### Security

Please see [SECURITY.md](./SECURITY.md) for more information on security disclosure process.
