<?php

namespace Ronanflavio\Easychat\Models;

class Room extends \Eloquent
{

    public static $unguarded = true;
    protected $table = 'ec_rooms';

    public function userA()
    {
        return $this->belongsTo('User', 'user_a');
    }

    public function userB()
    {
        return $this->belongsTo('User', 'user_b');
    }

}
