<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subsection extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'subheader', 'section_id', 'order', 'suborder'
    ];

    /**
     * The field
     */
    public function fields()
    {
        return $this->hasMany('App\Field');
    }

   

}
