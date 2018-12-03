<?php

namespace App\Http\Controllers;

use App\Event;
use App\EventRegistration;
use App\Policies\EventRegistrationPolicy;
use App\RegistrationValue;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EventRegistrationController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create($eventId)
    {
        $policy = new EventRegistrationPolicy;
        if (!$policy->register(Event::findOrFail($eventId))) {
            throw new AuthorizationException();
        }

        $registration = EventRegistration::where('event_id', $eventId)->where('user_id', auth()->user()->id);

        if ($registration->exists()) {
            return redirect()->route('events.registration.show', ['event' => $eventId, 'registration' => $registration->first()->id]);
        }

        $event = Event::findOrFail($eventId);

        return view("registrations.form", [
            'event' => $event,
            'user' => ['id' => auth()->user()->id],
            'mode' => 'create',
            'values' => []
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store($eventId, Request $request)
    {

        $event = Event::findOrFail($eventId);

        $validationRules = ['field-1' => 'required'];

        if ($request->input("field-1") === "Yes") {
            foreach ($event->fields as $field) {
                $required = $field->mandatory && $request->input("field-1") === 'Yes';
                if (!empty($field->condition)) {
                    $conditionFieldId = preg_split("~:~", $field->condition)[0];
                    $conditionValue = preg_split("~:~", $field->condition)[1];
                    if ($conditionValue !== $request->input("field-$conditionFieldId")) {
                        $required = false;
                    }
                }
                if ($required) {
                    $validationRules["field-$field->id"] = 'required';
                }
                if ($field->type === "team") {
                    $teamCapacity = 0;
                    foreach (preg_split('~;~', $field->options) as $team) {
                        $teamName = preg_split('~:~', $team)[0];
                        if ($teamName === $request->input("field-$field->id")) {
                            $teamCapacity = preg_split('~:~', $team)[1];
                        }
                    }
                    $validationRules["field-$field->id"] = [
                        'required',
                        function ($attribute, $value, $fail) use ($field, $event, $teamCapacity) {
                            $currentSize = $event->teams($field)[$value] ?? 0;
                            if ($currentSize >= $teamCapacity) {
                                $fail("Team $value is full, pick another one");
                            }
                        }
                    ];
                }
            }
        }

        $this->validate($request, $validationRules, ['required' => 'This field is required']);

        $registrationId = $request->input('registrationid');

        if (!empty($registrationId)) {
            $this->authorize('update', EventRegistration::findOrFail($registrationId));
        } else {
            $policy = new EventRegistrationPolicy;
            if (!$policy->register($event)) {
                throw new AuthorizationException();
            }
        }

        DB::transaction(function () use ($event, $request, &$registrationId) {

            if (empty($registrationId)) {
                $registration = new EventRegistration([
                    'user_id' => auth()->user()->id,
                    'event_id' => $event->id
                ]);
                $registration->save();
                $registrationId = $registration->id;
            }

            RegistrationValue::where('event_registration_id', $registrationId)->delete();

            $value = new RegistrationValue([
                'event_registration_id' => $registrationId,
                'field_id' => 1,
                'value' => $request->input("field-1")
            ]);

            $value->save();

            $value = new RegistrationValue([
                'event_registration_id' => $registrationId,
                'field_id' => 2,
                'value' => $request->input("field-2")
            ]);

            $value->save();


            foreach ($event->fields as $field) {

                if ($field->type === "doubletext") {
                    $content = $request->input("field-" . $field->id . "-1") . "|" . $request->input("field-" . $field->id . "-2");
                } else {
                    $content = $request->input("field-" . $field->id);
                }

                $value = new RegistrationValue([
                    'event_registration_id' => $registrationId,
                    'field_id' => $field->id,
                    'value' => $content
                ]);
                Log::info("Saving value");
                $value->save();
            }
        });

        return redirect("/events/$eventId/registration/$registrationId")->with('success', 'Your registration was saved');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show($eventId, $registrationId)
    {
        $event = Event::findOrFail($eventId);

        $registration = EventRegistration::findOrFail($registrationId);

        $this->authorize('view', $registration);

        if (!$registration->exists()) {
            redirect("events.registration.create", ['event' => $eventId]);
        }

        $values = RegistrationValue::where('event_registration_id', $registrationId);

        $values = $values->get()->mapWithKeys(function ($item) {
            return [$item['field_id'] => $item['value']];

        });


        return view("registrations.form", [
            'event' => $event,
            'user' => ['id' => auth()->user()->id],
            'values' => $values,
            'mode' => 'show',
            'registration' => $registration
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit($eventId, $registrationId)
    {
        $event = Event::findOrFail($eventId);
        $registration = EventRegistration::findOrFail($registrationId);

        $this->authorize('update', $registration);

        $values = RegistrationValue::where('event_registration_id', $registrationId);

        $values = $values->get()->mapWithKeys(function ($item) {
            return [$item['field_id'] => $item['value']];
        });

        return view("registrations.form", [
            'event' => $event,
            'user' => auth()->user(),
            'values' => $values,
            'mode' => 'edit',
            'registration' => $registration
        ]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
