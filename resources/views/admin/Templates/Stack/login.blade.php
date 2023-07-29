<!DOCTYPE html>
<html lang="{{app()->getLocale()}}" data-textdirection="{{((app()->getLocale()=='ar')?'rtl':'ltr')}}" class="loading">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="author" content="Mostafa Naguib">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }}</title>
    <link rel="apple-touch-icon" href="{{ app_asset('admin/images/ico/apple-icon-120.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ app_asset('admin/images/ico/favicon.ico') }}">
    <link rel="stylesheet" type="text/css" href="{{ app_asset('dashboard/css/app.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ app_asset('dashboard/css/'.((app()->getLocale()=='ar')?'rtl':'ltr').'-app.css') }}">
    <link href="{{app_asset('admin/css/custom.css')}}" rel="stylesheet" />

    @yield('header')
</head>
<body data-open="click" data-menu="vertical-menu" data-col="1-column" class="vertical-layout vertical-menu 1-column  blank-page blank-page">
<div class="app-content content container-fluid">
    <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">
            <section class="flexbox-container">
                <div class="col-md-4 offset-md-4 col-xs-10 offset-xs-1  box-shadow-2 p-0">
                    <div class="card border-grey border-lighten-3 m-0">
                        <div class="card-header no-border">
                            <div class="card-title text-xs-center">
                                <div class="p-1"><img src="{{ app_asset('images/logo.png') }}" alt="{{__('Logo')}}"></div>
                            </div>
                            <h6 class="card-subtitle line-on-side text-muted text-xs-center font-small-3 pt-2"><span>{{__('Login')}}</span></h6>
                        </div>
                        <div class="card-body collapse in">
                            <div class="card-block">
                                <form autocomplete="off" class="form-horizontal form-simple" method="post" action="{{ route('admin.login') }}" novalidate>
                                    {{ csrf_field() }}
                                    <fieldset  class="form-group{{ $errors->has('email') ? ' has-danger' : '' }} position-relative has-icon-left mb-0" style="margin-bottom: 7px !important;">
                                        <div class="controls">
                                            <input value="{{old('email')}}" autocomplete="off" type="text" class="form-control form-control-lg input-lg" id="user-name" placeholder="{{__('Enter Email')}}" data-validation-required-message="{{__('This field is required')}}" name="email" required>
                                            <div class="form-control-position">
                                                <i class="ft-user"></i>
                                            </div>
                                        </div>
                                    </fieldset>

                                    @if($errors->has('email'))
                                        <p class="text-xs-right">
                                            <small class="danger text-muted">
                                                @foreach($errors->get('email') as $error)
                                                    {{$error}} <br />
                                                @endforeach
                                            </small>
                                        </p>
                                    @endif

                                    <fieldset class="form-group {{$errors->has('password') ? ' has-danger' : ''}} position-relative has-icon-left" style="margin-bottom: 7px !important;">
                                        <input autocomplete="off" type="password" name="password" class="form-control form-control-lg input-lg" id="user-password" placeholder="{{__('Enter Your Password')}}" required>
                                        <div class="form-control-position">
                                            <i class="fa fa-key"></i>
                                        </div>
                                    </fieldset>

                                    @if($errors->has('password'))
                                        <p class="text-xs-right">
                                            <small class="danger text-muted">
                                                @foreach($errors->get('password') as $error)
                                                    {{$error}} <br />
                                                @endforeach
                                            </small>
                                        </p>
                                    @endif

                                    <fieldset class="form-group row">
                                        <div class="col-md-6 col-xs-12 text-xs-center text-md-left">
                                            <fieldset>
                                                <input type="checkbox" id="remember-me" name="remember" class="chk-remember">
                                                <label for="remember-me"> {{__('Remember Me')}}</label>
                                            </fieldset>
                                        </div>
                                    </fieldset>
                                    <button type="submit" class="btn btn-primary btn-lg btn-block"><i class="ft-unlock"></i> {{__('Login')}}</button>
                                </form>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="">
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

<!-- BEGIN VENDOR JS-->
<script src="{{ app_asset('dashboard/js/vendors.js') }}" type="text/javascript"></script>
<!-- BEGIN VENDOR JS-->
@if(app()->getLocale() == 'ar')
    <script src="{{ app_asset('admin/plugins/datepicker/js/locales/bootstrap-datetimepicker.ar.js') }}" type="text/javascript"></script>
@endif
<!-- BEGIN STACK JS-->
<script src="{{ app_asset('dashboard/js/stack.js') }}" type="text/javascript"></script>
</body>
</html>
