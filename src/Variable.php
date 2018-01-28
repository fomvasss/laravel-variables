<?php

namespace Fomvasss\Variable;

use Illuminate\Database\Eloquent\Model;

class Variable extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'value',
        'description',
        'locale',
    ];
}
