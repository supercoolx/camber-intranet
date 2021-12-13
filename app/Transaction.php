<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Transaction extends Model
{
    //
    protected $table = "transactions";

    public function user() {
        return $this->belongsTo(User::class, 'agent', 'id');
    }
}
