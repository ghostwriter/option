# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [2.0.0] - TBD

### Added

- Interface `Ghostwriter\Option\Interface\ExceptionInterface`
- Interface `Ghostwriter\Option\Interface\NoneInterface`
- Interface `Ghostwriter\Option\Interface\OptionInterface`
- Interface `Ghostwriter\Option\Interface\SomeInterface`
- Method `Ghostwriter\Option\None::new()`
- Method `Ghostwriter\Option\Some::new()`
- Method `Ghostwriter\Option\Some::nullable()`

### Changed

- All `callable` parameters changed to `Closure`
- Class `Ghostwriter\Option\None` no longer extends `Ghostwriter\Option\AbstractOption`
- Class `Ghostwriter\Option\Some` no longer extends `Ghostwriter\Option\AbstractOption`
- Class `Ghostwriter\Option\Exception\OptionException` no longer extends `RuntimeException`, now extends `InvalidArgumentException`

### Removed

- Class `Ghostwriter\Option\AbstractOption`
- Class `Ghostwriter\Option\Option`
- Interface `Ghostwriter\Option\Exception\OptionExceptionInterface`
- Interface `Ghostwriter\Option\NoneInterface`
- Interface `Ghostwriter\Option\OptionInterface`
- Interface `Ghostwriter\Option\SomeInterface`
- Method `Ghostwriter\Option\None::create()`
- Method `Ghostwriter\Option\Some::create()`

## [1.5.1] - 2023-07-05

### Added

- First version

[1.5.1]: https://github.com/ghostwriter/option/releases/tag/1.5.1
