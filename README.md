# Option

[![GitHub Sponsors](https://img.shields.io/github/sponsors/ghostwriter?label=Sponsor+@ghostwriter/option&logo=GitHub+Sponsors)](https://github.com/sponsors/ghostwriter)
[![Automation](https://github.com/ghostwriter/option/actions/workflows/automation.yml/badge.svg)](https://github.com/ghostwriter/option/actions/workflows/automation.yml)
[![Supported PHP Version](https://badgen.net/packagist/php/ghostwriter/option?color=8892bf)](https://www.php.net/supported-versions)
[![Downloads](https://badgen.net/packagist/dt/ghostwriter/option?color=blue)](https://packagist.org/packages/ghostwriter/option)
tions/workflows/automation.yml/badge.svg)](https://github.com/ghostwriter/option/actions/workflows/automation.yml)
[![Supported PHP Version](https://badgen.net/packagist/php/ghostwriter/option?color=8892bf&cache=600&)](https://www.php.net/supported-versions)
[![Latest Version on Packagist](https://badgen.net/packagist/v/ghostwriter/option?cache=600)](https://packagist.org/packages/ghostwriter/option)
[![Downloads](https://badgen.net/packagist/dt/ghostwriter/option?cache=600&color=blue)](https://packagist.org/packages/ghostwriter/option)

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

$greeting = Some::new('Hello World!');
echo $greeting->get();        // 'Hello World!'


$name = None::new();
echo $name->get();                  // throw `NullPointerException`
echo $name->getOr('Default Value'); // 'Default Value'

None::new();            // return `None`
Some::nullable(null);   // return `None`
Some::new(null);        // throw `NullPointerException`

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
