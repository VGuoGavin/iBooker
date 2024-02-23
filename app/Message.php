<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    public $timestamps = false;

    protected function setKeysForSaveQuery(Builder $query)
    {
        $query
            ->where('sender_id', '=', $this->getAttribute('sender_id'))
            ->where('receiver_id', '=', $this->getAttribute('receiver_id'))
            ->where('sent_at', '=', $this->getAttribute('sent_at'));
        return $query;
    }
}
