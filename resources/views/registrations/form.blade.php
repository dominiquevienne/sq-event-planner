@extends('layouts.app')

@section('title', $event->name." - Registration")

@section('styles')
    <link rel="stylesheet" href="/css/themes/{{$event->theme}}.css"/>
@endsection

@section('bodyprops')id="form-sq-party"@endsection

@section('containerclass','form')

@section('content')

    <h1 style="font-size: 3.5em;color:#fa5b35">{{$event->header}}</h1>
    <h1>{{$event->name}}</h1>
    <div class="card-group col-sm-10 ">
        <div class="card" style="width: 18rem;">
            <div class="card-body">
                <p class="card-text">
                    <svg style="margin-top:-4px" width="18" height="18" viewBox="0 0 14 16" version="1.1" aria-hidden="true">
                        <path fill-rule="evenodd"
                              d="M13 2h-1v1.5c0 .28-.22.5-.5.5h-2c-.28 0-.5-.22-.5-.5V2H6v1.5c0 .28-.22.5-.5.5h-2c-.28 0-.5-.22-.5-.5V2H2c-.55 0-1 .45-1 1v11c0 .55.45 1 1 1h11c.55 0 1-.45 1-1V3c0-.55-.45-1-1-1zm0 12H2V5h11v9zM5 3H4V1h1v2zm6 0h-1V1h1v2zM6 7H5V6h1v1zm2 0H7V6h1v1zm2 0H9V6h1v1zm2 0h-1V6h1v1zM4 9H3V8h1v1zm2 0H5V8h1v1zm2 0H7V8h1v1zm2 0H9V8h1v1zm2 0h-1V8h1v1zm-8 2H3v-1h1v1zm2 0H5v-1h1v1zm2 0H7v-1h1v1zm2 0H9v-1h1v1zm2 0h-1v-1h1v1zm-8 2H3v-1h1v1zm2 0H5v-1h1v1zm2 0H7v-1h1v1zm2 0H9v-1h1v1z"></path>
                    </svg>
                    {{$event->time->format('l d-m-Y')}}</p>
                <p class="card-text">
                    <svg style="margin-top:-2px" width="18" height="18" class="octicon octicon-clock" viewBox="0 0 14 16" version="1.1"
                         aria-hidden="true">
                        <path fill-rule="evenodd"
                              d="M8 8h3v2H7c-.55 0-1-.45-1-1V4h2v4zM7 2.3c3.14 0 5.7 2.56 5.7 5.7s-2.56 5.7-5.7 5.7A5.71 5.71 0 0 1 1.3 8c0-3.14 2.56-5.7 5.7-5.7zM7 1C3.14 1 0 4.14 0 8s3.14 7 7 7 7-3.14 7-7-3.14-7-7-7z"></path>
                    </svg>
                    {{$event->time->format('H:i T')}}</p>
            </div>
        </div>
        <div class="card" style="width: 18rem;">
            <div class="card-body">
                <p class="card-text">
                    <svg style="margin-top:-2px" width="18" height="18" class="octicon octicon-location" viewBox="0 0 12 16" version="1.1"
                         aria-hidden="true">
                        <path fill-rule="evenodd"
                              d="M6 0C2.69 0 0 2.5 0 5.5 0 10.02 6 16 6 16s6-5.98 6-10.5C12 2.5 9.31 0 6 0zm0 14.55C4.14 12.52 1 8.44 1 5.5 1 3.02 3.25 1 6 1c1.34 0 2.61.48 3.56 1.36.92.86 1.44 1.97 1.44 3.14 0 2.94-3.14 7.02-5 9.05zM8 5.5c0 1.11-.89 2-2 2-1.11 0-2-.89-2-2 0-1.11.89-2 2-2 1.11 0 2 .89 2 2z"></path>
                    </svg>
                    {{$event->location}}
                </p>
                <a target="_blank" href="{{$event->gmap}}" class="card-link">Open in Google Maps</a>
            </div>
        </div>
        <div class="card" style="width: 18rem;">
            <div class="card-body">
                <p class="card-text">
                    <svg style="margin-top:-2px" width="18" height="18" class="octicon octicon-pencil" viewBox="0 0 14 16" version="1.1"
                         aria-hidden="true">
                        <path fill-rule="evenodd"
                              d="M0 12v3h3l8-8-3-3-8 8zm3 2H1v-2h1v1h1v1zm10.3-9.3L12 6 9 3l1.3-1.3a.996.996 0 0 1 1.41 0l1.59 1.59c.39.39.39 1.02 0 1.41z">
                        </path>
                    </svg>
                    Registration deadline
                </p>
                <p class="card-text">
                    {{$event->registration_deadline->format('d-m-Y H:i T')}}
                </p>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <br>
        <div class="col-sm-10">
            <div class="alert alert-danger">
                There are validation errors, please fix them and try again.
            </div>
        </div>
    @endif

    @if($mode === 'show' && $event->registration_deadline->isFuture())
        <br>
        <div class="row">
            <div class="col-sm-10">
                <div class="alert alert-success">
                    We have your answer for this event. You can modify it until {{$event->registration_deadline->format('l d-m-Y H:i T')}}.
                </div>
            </div>
        </div>
    @endif

    @if($mode === 'show' && $event->registration_deadline->isPast())
        <br>
        <div class="col-sm-10">
            <div class="alert alert-warning">
                We have your answer for this event. The registration deadline has passed and you cannot modify it. For exceptional changes,
                contact
                sqlife@swissquote.ch.
            </div>
        </div>
    @endif

    @if($mode === 'show' && $event->practical_infos != null)

        <div class="col-sm-10">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Practical Infos</h5>
                    <p class="card-text">{!! nl2br(e($event->practical_infos)) !!}</p>
                </div>
            </div>
        </div>

    @endif

    <div>
        <br>
        <form action="{{route('events.registration.store', ['event' => $event])}}" method="post">
            {{ csrf_field() }}
            @if(!empty($registration))
                <input type="hidden" name="registrationid" value="{{$registration->id}}">
            @endif

            <?php
            $participationField = new stdClass();
            $participationField->id = 1;
            $participationField->label = "Will you attend this event?";
            $participationField->type = "radio";
            $participationField->options = "Yes;No";
            $participationField->help = "";
            $participationField->condition = "";

            $reasonField = new stdClass();
            $reasonField->id = 2;
            $reasonField->label = "Why not? (optional)";
            $reasonField->type = "text";
            $reasonField->condition = "1:No";
            $reasonField->placeholder = "";
            $reasonField->help = "";
            $reasonField->condition = "";
            ?>

            @include('forms.radio', ['field' => $participationField, 'mode' => $mode, 'value' => old("field-1") ?? $values[1] ?? '', 'errors' => $errors->get('field-1')])
            @include('forms.text', ['field' => $reasonField, 'mode' => $mode, 'value' => old("field-2") ?? $values[2] ?? '', 'class' => 'hidden', 'errors' => $errors->get('field-2')])


            <div id="participant-form" style="display:none">
                @foreach($event->fields as $field)
                    @include('forms.'.$field->type, ['field' => $field, 'mode' => $mode, 'value' => old("field-$field->id") ?? $values[$field->id] ?? '', 'errors' => $errors->get("field-$field->id")])
                @endforeach
            </div>

            @if($mode === 'create' || $mode === 'edit')
                <button type="submit" class="btn btn-primary">Submit</button>
            @endif

            @if($mode === 'show' && $event->registration_deadline->isFuture())
                <a href="{{route('events.registration.edit', ['event' => $event, 'registration' => $registration])}}" class="btn btn-primary">Modify
                    registration</a>
            @endif

        </form>


    </div>


@endsection
