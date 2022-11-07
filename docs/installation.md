---
title: Installation
sort: 3
---

## Installation

You can install the package via composer:

```bash
composer require rawilk/laravel-app-key-rotator
```

## Configuration

You should publish the config file:

```bash
php artisan vendor:publish --tag="app-key-rotator-config"
```

You can view the default configuration here: https://github.com/rawilk/laravel-app-key-rotator/blob/{branch}/config/app-key-rotator.php
