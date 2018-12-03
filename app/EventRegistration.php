<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class EventRegistration
 * @package App
 *
 * @property Event $event
 */
class EventRegistration extends Model
{
    protected $fillable = ['user_id', 'event_id'];

    public function event()
    {
        return $this->belongsTo('App\Event', 'event_id');
    }

}
