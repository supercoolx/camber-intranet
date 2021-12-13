<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    protected $fillable = [
        'event', 'description', 'subject_agent', 'body_agent', 'subject_admin', 'body_admin', 'is_active'
    ];

}
