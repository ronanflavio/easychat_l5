<?php

namespace Ronanflavio\Easychat\Models;

class ServerMessage extends \Eloquent
{

    public static $unguarded = true;
    protected $table = 'ec_server_messages';

    public function sentBy()
    {
        return $this->belongsTo('User', 'sent_by');
    }

    public function sentTo()
    {
        return $this->belongsTo('User', 'sent_to');
    }

    public function room()
    {
        return $this->belongsTo('Room');
    }

}
