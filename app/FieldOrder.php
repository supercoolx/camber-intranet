<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FieldOrder extends Model
{
    protected $table = 'field_order';

    public function requests()
    {
        return $this->hasOne('App\Field','id');
    }
}
