# Changelog

All notable changes to `laravel-app-key-rotator` will be documented in this file

## v3.0.0 - 2022-11-07

### What's Changed

- Bump dependabot/fetch-metadata from 1.3.4 to 1.3.5 by @dependabot in https://github.com/rawilk/laravel-app-key-rotator/pull/3
- Bump actions/checkout from 2 to 3 by @dependabot in https://github.com/rawilk/laravel-app-key-rotator/pull/2
- Bump creyD/prettier_action from 3.0 to 4.2 by @dependabot in https://github.com/rawilk/laravel-app-key-rotator/pull/4
- App key rotation now performed from dedicated action class instead of directly in the command
- Custom actions now support custom arguments to be passed into their constructors

### Breaking Changes

- Drop PHP 7.4 support
- Drop Laravel 8.0 support
- Package config and `AppKeyRotator` instance are now passed into `handle()` instead of `__construct` on each action
- `models` config key now expects a single dimensional array of model classes
- Models requiring re-encryption must implement the `\Rawilk\AppKeyRotator\Contracts\ReEncryptsData` contract and implement the `encryptedProperties` method

**Full Changelog**: https://github.com/rawilk/laravel-app-key-rotator/compare/v2.0.1...v3.0.0

## 2.0.1 - 2022-02-23

### Updated

- Add support for Laravel 9.*
- Add support for PHP 8.0
- Add support for PHP 8.1

## 2.0.0 - 2020-09-09

### Added

- Add support for Laravel 8

### Removed

- Drop support for Laravel 7

## 1.0.0 - 2020-07-23

- initial release
