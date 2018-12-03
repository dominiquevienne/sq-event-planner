@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="/css/themes/quest.css"/>
@endsection

@section('content')
<div class="items-container-squest">
    <div class="container">
        <div class="row justify-content-center" style="margin-top: 20px; margin-bottom:20px">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header text-md-center"><br><h4>Identity scan</h4></div>
                    <div style="text-align: center">
                        Please enter your usual Swissquote login.<br>Those you use to open your windows session.
                    </div>


                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="form-group row">
                                <label for="login" class="col-sm-4 col-form-label text-md-right">{{ __('Login') }}</label>

                                <div class="col-md-6">
                                    <input id="login" type="text" class="form-control{{ $errors->has('login') ? ' is-invalid' : '' }}" name="login" value="{{ old('login') }}" required autofocus>

                                    @if ($errors->has('login'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('login') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

<!--                            <div class="form-group row">-->
<!--                                <div style="margin-left: 12px;" class="col-md-10 text-md-center">-->
<!--                                    <div class="form-check">-->
<!--                                        <input class="form-check-input" type="checkbox" name="remember" id="remember">-->
<!---->
<!--                                        <label style="font-size: 14px;"class="form-check-label align-items-start" for="remember">-->
<!--                                           {{ __('Remember Me') }}-->
<!--                                        </label>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Register Now
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
