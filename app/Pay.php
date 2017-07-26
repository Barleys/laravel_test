<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pay extends Model
{
    protected $table = 'pay';

    public function belongsToUser()
    {
        return $this->belongsTo('User', 'user_id', 'id');
    }


}
