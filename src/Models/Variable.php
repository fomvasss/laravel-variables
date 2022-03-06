<?php

namespace Fomvasss\Variable\Models;

use Illuminate\Database\Eloquent\Model;

class Variable extends Model
{
    public $table = 'variables';

    public $timestamps = false;

    public $guarded = ['id'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('variables.table_name'));
    }

    protected static function booted()
    {
        static::saved(function ($model) {
            if (config('variables.cache.autoclear')) {
                app(\Fomvasss\Variable\VariableManagerContract::class)->cacheClear();
            }
        });

        foreach (config('variables.prepare_scopes', []) as $class) {
            if (class_exists($class)) {
                static::addGlobalScope(new $class);
            }
        }
    }

    public function byLangcode($query, ?string $langcode = null)
    {
        return $query->when($langcode, function ($q) use ($langcode) {
           $q->where('langcode', $langcode);
        });
    }
}
