<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{

    /**
     * The field
     */
    public function subsections()
    {
        return $this->hasMany('App\Subsection')->orderBy('order')->orderBy('suborder');
    }
}
