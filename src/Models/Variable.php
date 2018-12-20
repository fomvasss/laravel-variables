<?php

namespace Fomvasss\Variable\Models;

use Illuminate\Database\Eloquent\Model;

class Variable extends Model
{
    public $timestamps = false;

    public $guarded = ['id'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('variables.table_name'));
    }
}
