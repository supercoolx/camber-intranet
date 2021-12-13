<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    public function fields()
    {
        return $this->belongsToMany('\App\Field')->withPivot('value');
    }

    /**
     * The field without meta data
     */
    public function fielsdWithoutMetaData()
    {
        return $this->belongsToMany('App\Field');
    }

    /**
     * The reuest without meta data
     */
    public function requestsWithoutMetaData()
    {
        return $this->belongsToMany('App\Request');
    }

    /**
     * The assistant received last changes
     */
    public function assistant()
    {
        return $this->hasOne('App\User', 'id', 'assistant_id');
    }

    public function agent()
    {
        return $this->belongsTo('App\User');
    }

    public function requests()
    {
        return $this->hasMany('App\OrderRequest');
    }

}
