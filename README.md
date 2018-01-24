<h1 align="center">
    Countries for Laravel
</h1>

![World Map](https://raw.githubusercontent.com/antonioribeiro/countries/master/docs/world-map-political-of-the-2013-nations-online-project-best.jpg)

<p align="center">
    <a href="https://packagist.org/packages/pragmarx/countries"><img alt="Latest Stable Version" src="https://img.shields.io/packagist/v/pragmarx/countries.svg?style=flat-square"></a>
    <a href="/antonioribeiro/countries/blob/master/LICENSE.md"><img alt="License" src="https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square"></a>
    <a href="https://scrutinizer-yaml.com/g/antonioribeiro/countries/?branch=master"><img alt="Code Quality" src="https://img.shields.io/scrutinizer/g/antonioribeiro/countries.svg?style=flat-square"></a>
    <a href="https://scrutinizer-yaml.com/g/antonioribeiro/countries/?branch=master"><img alt="Build" src="https://img.shields.io/scrutinizer/build/g/antonioribeiro/countries.svg?style=flat-square"></a>
</p>
<p align="center">
    <a href="https://scrutinizer-yaml.com/g/antonioribeiro/countries/?branch=master"><img alt="Coverage" src="https://img.shields.io/scrutinizer/coverage/g/antonioribeiro/countries.svg?style=flat-square"></a>
    <a href="https://travis-ci.org/antonioribeiro/countries"><img alt="PHP" src="https://img.shields.io/badge/PHP-7.0%20%7C%207.1%20%7C%207.2%20%7C%20nightly-green.svg?style=flat"></a>
    <a href="https://packagist.org/packages/pragmarx/countries"><img alt="Downloads" src="https://img.shields.io/packagist/dt/pragmarx/countries.svg?style=flat-square"></a>
    <a href="https://styleci.io/repos/74829244"><img alt="StyleCI" src="https://styleci.io/repos/74829244/shield"></a>
</p>

### What does it gives you?

This package has all sorts of information about countries:

| info            | items |
------------------|-------:|
| taxes           | 32    |
| geometry map    | 248   |
| topology map    | 248   |
| currencies      | 256   |
| countries       | 266   |
| timezones       | 423   |
| borders         | 649   |
| flags           | 1,570  |
| states          | 4,526  |
| cities          | 7,376  |
| timezones times | 81,153 |

### This package is a Laravel bridge 

Please refer to the [main package repository](https://github.com/antonioribeiro/countries) for more information and docs.

## Requirements

- PHP 7.0+
- Laravel 5.5+

## Installing

Use Composer to install it:

```
composer require pragmarx/countries-laravel
```

## Usage

The package is based on Laravel Collections, so you basically have access to all methods in Collections, like

```php
$france = Countries::where('name.common', 'France');
```

## Author

[Antonio Carlos Ribeiro](http://twitter.com/iantonioribeiro)

## License

Countries is licensed under the MIT License - see the `LICENSE` file for details

## Contributing

Pull requests and issues are more than welcome.
