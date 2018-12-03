<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * @property int $id
 * @property Carbon $time
 * @property Carbon $registration_deadline
 * @property string $name
 * @property string $theme
 * @property Field[] $fields
 * @property Field[] $answerFields
 * @property mixed $teams
 */
class Event extends Model
{

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'time',
        'registration_deadline'
    ];

    //
    public function fields()
    {
        return $this->belongsToMany('App\Field', 'event_fields')->orderBy('event_fields.order', 'asc');
    }

    //
    public function answerFields()
    {
        return $this->belongsToMany('App\Field', 'event_fields')->where('fields.type', "!=", 'header')->orderBy('event_fields.order', 'asc');
    }

    /* returns an array with the number of persons in each possible team */
    public function teams($field)
    {
        return DB::table('registration_values')
            ->select('value', DB::raw('count(id) as size'))
            ->join('event_registrations', 'registration_values.event_registration_id', '=', 'event_registrations.id')
            ->groupBy('value')
            ->where('event_registrations.event_id', $this->id)
            ->where('registration_values.field_id', $field->id)->get()->mapWithKeys(function ($row) {
                return [$row->value => $row->size];
            });
    }

    public function registration($userId)
    {
        return EventRegistration::where('user_id', $userId)->where('event_id', $this->id)->first();
    }

}
