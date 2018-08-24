# Changelog

## 0.6.0 - 2018-08-19
### Changed
- Please check countries package changelog
### Breaking Change
If you are using this package caching system, you have to Updated cache.services in the contries.php configuration file. Ensure to update your configuration the below before updating:
```
'cache' => [
  'service' =>  PragmaRX\Countries\Package\Services\Cache\Service::class,
]
```

## 0.1.0 - 2018-01-22
### Added
- First version
