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
For Laravel > 5.5 add to the service-providers list in app.php next
providers:
```php
	Fomvasss\Taxonomy\FieldFileServiceProvider::class,
```
aliases:
```php
	'Variable' => Fomvasss\Variable\Facades\Variable::class,
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
### Helpers
- `var_all($locale = null)`
- `var_get($name, $default = null, $locale = null)`
- `var_set($name, $value = null, $locale = null)`
- `var_delete($name, $locale = null)`
- `var_set_array(array $attributes, $locale = null)`

### Facade
```php
use Fomvasss\Variable\Facades\Variable;

Variable::set('site_name', 'My Site);
Variable::all();
Variable::get('site_name');
Variable::delete('site_name');
Variable::setArray(['titles' => 'TiTlE', 'slugan' => 'Lorem ipsun!']);
```
You can use the localization vars:
```php
Variable::locale('en')->all();
Variable::locale('ru')->delete('site_name');
```

### DI VariableManagerContract class
```php
function (\Fomvasss\Variable\VariableManagerContract $mng)
{
	$mng->get('site_name');
}
```

### Console command
```bash
variable:get-all     # Get all variables
variable:get     # Get one variable
variable:delete  # Delete variable
variable:set     # Get all variables

php artisan cache:forget fomvasss.variables.cache
```
