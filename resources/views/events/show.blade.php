@extends('layouts.app')

@section('title', $event->name)

@section('styles')
    <link rel="stylesheet" href="/css/themes/{{$event->theme}}.css"/>
@endsection

@section('content')

    {{--<h1>{{$event->name}}</h1>--}}

    {{--TODO : Embedded video (add URL to DB) !--}}

    {{--<br><br>--}}

    {{--@if ($registration == null && $event->registration_deadline->isFuture())--}}
    {{--<a href="{{route('events.registration.create', ['event' => $event])}}">Register Now!</a>--}}
    {{--@endif--}}

    {{--@if ($registration == null && $event->registration_deadline->isPast())--}}
    {{--Registrations for this event are closed, for last minute registrations, contact sqlife@swissquote.ch--}}
    {{--@endif--}}

    {{--@if ($registration != null)--}}
    {{--We have your answers for this event.--}}
    {{--<a href="{{route('events.registration.show', ['event' => $event, 'registration' => $registration->id])}}">View registration</a>--}}
    {{--@endif--}}

    <div class="squest-homepage">

        <div class="items-container-squest">

            <div class="space-quest"></div>

            <div class="text-quest"><b>Year 2156</b><br/><br/>The World lives (almost) in peace thanks to the Artificial Intelligence developed
                by Swissquote one century ago.  <br/> Until… <br/><br/> One day, Swissquote’s AI received an unidentified signal from outer space…<br/> An invitation
                from an unknown planet. Is it an opportunity or a trap?<br/> To find out, a group of Swissquote Pioneers charters a spaceship to
                follow the signal.<br/><br/> You are a member of these Pioneers.<br/> Let’s go to infinity and beyond!

                <br/>
                <div style="margin-top: 5em;">
                    @if ($registration == null && $event->registration_deadline->isFuture())
                        <a class="btn-form" href="{{route('events.registration.create', ['event' => $event])}}">Join the adventure!</a>
                    @endif

                    @if ($registration == null && $event->registration_deadline->isPast())
                        <div style="font-size:1.8em">Registrations for this event are closed, for last minute registrations, contact
                            sqlife@swissquote.ch
                        </div>
                    @endif

                    @if ($registration != null)
                        <a class="btn-form"
                           href="{{route('events.registration.show', ['event' => $event, 'registration' => $registration->id])}}">Join the adventure!</a>
                    @endif
                    {{--<a class="btn-form" href="http://sq-events.sbuild.bank.swissquote.ch/events/1/registration/create">Register Now!</a>--}}
                </div>
            </div>


        </div>

    </div>

@endsection
