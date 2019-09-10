<h1 align="center">
    Countries for Laravel
</h1>

![World Map](https://raw.githubusercontent.com/antonioribeiro/countries/master/docs/world-map-political-of-the-2013-nations-online-project-best.jpg)

<p align="center">
    <a href="https://packagist.org/packages/pragmarx/countries-laravel"><img alt="Latest Stable Version" src="https://img.shields.io/packagist/v/pragmarx/countries-laravel.svg?style=flat-square"></a>
    <a href="/antonioribeiro/countries/blob/master/LICENSE.md"><img alt="License" src="https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square"></a>
    <a href="https://scrutinizer-yaml.com/g/antonioribeiro/countries-laravel/?branch=master"><img alt="Code Quality" src="https://img.shields.io/scrutinizer/g/antonioribeiro/countries-laravel.svg?style=flat-square"></a>
    <a href="https://scrutinizer-yaml.com/g/antonioribeiro/countries-laravel/?branch=master"><img alt="Build" src="https://img.shields.io/scrutinizer/build/g/antonioribeiro/countries-laravel.svg?style=flat-square"></a>
</p>
<p align="center">
    <a href="https://scrutinizer-yaml.com/g/antonioribeiro/countries-laravel/?branch=master"><img alt="Coverage" src="https://img.shields.io/scrutinizer/coverage/g/antonioribeiro/countries-laravel.svg?style=flat-square"></a>
    <a href="https://travis-ci.org/antonioribeiro/countries-laravel"><img alt="PHP" src="https://img.shields.io/badge/PHP-7.0%20%7C%207.1%20%7C%207.2%20%7C%207.3-brightgreen.svg?style=flat"></a>
    <a href="https://packagist.org/packages/pragmarx/countries-laravel"><img alt="Downloads" src="https://img.shields.io/packagist/dt/pragmarx/countries-laravel.svg?style=flat-square"></a>
    <a href="https://styleci.io/repos/118451602"><img alt="StyleCI" src="https://styleci.io/repos/118451602/shield"></a>
</p>

### What does it gives you?

This package has all sorts of information about countries:

| info            | items |
------------------|-------:|
| taxes           | 32    |
| geometry maps   | 248   |
| topology maps   | 248   |
| currencies      | 256   |
| countries       | 266   |
| timezones       | 423   |
| borders         | 649   |
| flags           | 1,570  |
| states          | 4,526  |
| cities          | 7,376  |
| timezones times | 81,153 |

### Validation

The validation is extending Laravel's validation, so you can use it like any other validation rules, like

```php
/**
 * Store a new blog post.
 *
 * @param  Request  $request
 * @return Response
 */
public function store(Request $request)
{
    $this->validate($request, [
        'title' => 'required|unique:posts|max:255',
        'body' => 'required',
        'country' => 'country' //Checks if valid name.common
    ]);

    // The blog post is valid, store in database...
}
```

Which validation rules there is and what there name should be, can all be configured in the configuration file.

```php
'validation' => [
    'rules' => [
	    'countryCommon' => 'name.common'
	]
]
```

By changing the configuration like this, we can now access the property `name.common`, by the validation rule `countryCommon`

You have to define all the validations rules in settings, only a few is defined by default, the default is

```php
'rules' 	=> [
    'country' 			=> 'name.common',
    'cca2',
    'cca3',
    'ccn3',
    'cioc',
    'currencies'			=> 'ISO4217',
    'language',
    'language_short'	=> 'ISO639_3',
]
```

### Documentation

This package is a Laravel bridge, please refer to the [main package repository](https://github.com/antonioribeiro/countries) for more information and docs.

## Requirements

- PHP 7.0+
- Laravel 5.5+

## Installing

Use Composer to install it:

```
composer require pragmarx/countries-laravel
```

## Publishing assets

You can publish configuration by doing:
```
php artisan vendor:publish --provider=PragmaRX\\CountriesLaravel\\Package\\ServiceProvider
```

## Usage

After installing you'll have access to the Countries Façade, and the package is based on Laravel Collections, so you basically have access to all methods in Collections, like

```php
$france = Countries::where('name.common', 'France');
```

## Flag routes

You can refer directly to an SVG flag by linking 

```
/pragmarx/countries/flag/download/<cca3-code>.svg
/pragmarx/countries/flag/file/<cca3-code>.svg
```

Examples:

```
https://laravel.com/pragmarx/countries/flag/download/usa.svg
https://laravel.com/pragmarx/countries/flag/file/usa.svg
```

http://pragmarx.test/pragmarx/countries/flag/file/usa.svg

## Author

[Antonio Carlos Ribeiro](http://twitter.com/iantonioribeiro)

## License

Countries is licensed under the MIT License - see the `LICENSE` file for details

## Contributing

Pull requests and issues are more than welcome.
