[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/support-ukraine.svg?t=1" />](https://supportukrainenow.org)

# Laravel Variables

[![Latest Stable Version](https://poser.pugx.org/fomvasss/laravel-variables/v/stable)](https://packagist.org/packages/fomvasss/laravel-variables)
[![Total Downloads](https://poser.pugx.org/fomvasss/laravel-variables/downloads)](https://packagist.org/packages/fomvasss/laravel-variables)
[![Latest Unstable Version](https://poser.pugx.org/fomvasss/laravel-variables/v/unstable)](https://packagist.org/packages/fomvasss/laravel-variables)
[![License](https://poser.pugx.org/fomvasss/laravel-variables/license)](https://packagist.org/packages/fomvasss/laravel-variables)
[![Quality Score](https://img.shields.io/scrutinizer/g/fomvasss/laravel-variables.svg?style=flat-square)](https://scrutinizer-ci.com/g/fomvasss/laravel-variables)

Dynamic management of variables/configs in Laravel app: creating and updating they in database, using cache and artisan commands, replace default Laravel configs, etc.

## Installation
Run:
```bash
composer require fomvasss/laravel-variables
```
---

Publish the config, migration:
```bash
php artisan vendor:publish --provider="Fomvasss\Variable\VariableServiceProvider"
```

Run migrate:
```bash
php artisan migrate
```

---
## Usage

### Facade `Variable`

```php
<?php
Variable::all();
Variable::get('var_key');
Variable::save('app_name', 'My Var');
```

Use groupped (multilanguages) variables:
```php
<?php
Variable::setGroup('en')->all(); // return Collection!
Variable::setGroup('uk')->get('var_key');
Variable::get('var_key', null, 'en'); 
Variable::get('en|var_key');
Variable::save('uk|var_key', 'UK var');
```

Use array (json) variables:
```php
Variable::saveArray('links', ['https::google.com', 'https://laravel.com']);   // save PHP array
Variable::getArray('links');    // return default PHP array!
```

Use cache variables:
```php
Variable::setGroup('uk')->save('app_name', 'Blog');
Variable::setGroup('uk')->useCache(false)->get('app_name');
//or
Variable::get('var_key', null, 'uk', false);
```

### Helpers
```php
variable($name, $default = null, $group = null);
```


### Replace Laravel configs with variables

Set in `config/variables.php` option `config_key_for_vars=vars`

Add keys in `variable_config` array: `variable_key => config_key`

### Console command
```bash
variable:all            # Show all variables
variable:get            # Get single variable
variable:save           # Save single variable
variable:cache-clear    # Cache clear all variables
```

### Use cache
Set in `config/variables.php` option `cache.time` seconds for cache.

Clear variable cache with console:
```bash
php artisan variable:cache-clear
```
or
```bash
php artisan cache:forget laravel.variables.cache
```

Clear variable cache in controller after update var:
```php
Variable::cacheClear();
//or
\Cache::forget('laravel.variables.cache');
```
