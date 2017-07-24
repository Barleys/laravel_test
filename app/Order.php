<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User', 'userid', 'id');
    }

    public function getOrdersById($userid)
    {
        $orders = Order::whereHas('user', function($q) use($userid){
            $q->where('id', $userid);
        })->with('user')->get();

        return $orders;
    }
}
