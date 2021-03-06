<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoleAttribute extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'value'
    ];
}
