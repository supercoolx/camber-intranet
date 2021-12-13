<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    /**
     * Get role by name
     *
     * @param [eloquent] $query
     * @param [string] $role
     * @return void
     */
    public function scopeGetRole($query, $role)
    {
        return $query->where('name', $role);
    }
}
