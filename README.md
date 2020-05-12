# Laravel Variables

[![Latest Stable Version](https://poser.pugx.org/fomvasss/laravel-variables/v/stable)](https://packagist.org/packages/fomvasss/laravel-variables)
[![Total Downloads](https://poser.pugx.org/fomvasss/laravel-variables/downloads)](https://packagist.org/packages/fomvasss/laravel-variables)
[![Latest Unstable Version](https://poser.pugx.org/fomvasss/laravel-variables/v/unstable)](https://packagist.org/packages/fomvasss/laravel-variables)
[![License](https://poser.pugx.org/fomvasss/laravel-variables/license)](https://packagist.org/packages/fomvasss/laravel-variables)
[![Quality Score](https://img.shields.io/scrutinizer/g/fomvasss/laravel-variables.svg?style=flat-square)](https://scrutinizer-ci.com/g/fomvasss/laravel-variables)

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

### Facade

```php
<?php
use Fomvasss\Variable\Facades\Variable;

Variable::all();
Variable::get('var_key');
Variable::set('var_key', 'var_value');
```

You can use the localization vars:
```php
<?php
Variable::locale('en')->all();
Variable::locale('ru')->get('var_key');
```

### DI VariableManagerContract class
```php
function (\Fomvasss\Variable\VariableManagerContract $variable)
{
	$variable->get('app_name');
}
```

### Helpers
- `variable($name, $default = null, $locale = null)`

### Replace configs with variables

Set in `config/variables.php` option `config_key_for_vars=vars`

Add keys in `variable_config` array: variable_key = config_key

### Console command
```bash
variable:all     # Get all variables
variable:get     # Get one variable
```

### Use cache
Set in `config/variables.php` option `cache.time` seconds for cache.

Clear variable cache with console:
```bash
php artisan cache:forget laravel.variables.cache
```

Clear variable cache in controller after update var:
```php
Variable::cacheClear();
```
or
```php
\Cache::forget('laravel.variables.cache');
```
