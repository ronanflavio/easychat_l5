<?php

namespace Ronanflavio\Easychat\Models;

class UserMessage extends \Eloquent
{

    public static $unguarded = true;
    protected $table = 'ec_user_messages';

    public function sentBy()
    {
        return $this->belongsTo('User', 'sent_by');
    }

    public function sentTo()
    {
        return $this->belongsTo('User', 'sent_to');
    }

    public function serverMessage()
    {
        return $this->belongsTo('ServerMessage');
    }

    public function text()
    {
        return $this->serverMessage->text;
    }

}
