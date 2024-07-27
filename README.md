# Option

[![Automation](https://github.com/ghostwriter/option/actions/workflows/automation.yml/badge.svg)](https://github.com/ghostwriter/option/actions/workflows/automation.yml)
[![Supported PHP Version](https://badgen.net/packagist/php/ghostwriter/option?color=8892bf&cache=600&)](https://www.php.net/supported-versions)
[![Mutation Coverage](https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Fghostwriter%2Foption%2Fmain)](https://dashboard.stryker-mutator.io/reports/github.com/ghostwriter/option/main)
[![Code Coverage](https://codecov.io/gh/ghostwriter/option/branch/main/graph/badge.svg)](https://codecov.io/gh/ghostwriter/option)
[![Type Coverage](https://shepherd.dev/github/ghostwriter/option/coverage.svg)](https://shepherd.dev/github/ghostwriter/option)
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
echo $greeting->unwrap();        // 'Hello World!'


$name = None::new();
echo $name->unwrap();                  // throw `NullPointerException`
echo $name->unwrapOr('Default Value'); // 'Default Value'

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
