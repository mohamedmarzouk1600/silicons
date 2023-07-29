<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>{{ $title }}</title>
    <!-- Favicon-->
    <link rel="icon" href="{{app_asset('admin/lorax/images/favicon.ico')}}" type="image/x-icon">
    <!-- Plugins Core Css -->
    <link href="{{app_asset('admin/lorax/css/app.min.css')}}" rel="stylesheet">
    <!-- Custom Css -->
    <link href="{{app_asset('admin/lorax/css/style.css')}}" rel="stylesheet" />
    <link href="{{app_asset('admin/lorax/css/pages/authentication.css')}}" rel="stylesheet" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body dir="{{((app()->getLocale()=='ar')?'rtl':'ltr')}}">
<div class="limiter formCard">
    <div class="container-login100 ">
        <div class="wrap-login100">
            <form method="POST" class="login100-form wrapper" action="{{ route('admin.login') }}">
                {{ csrf_field() }}
					<span class="login100-form-title p-b-45">
						{{__('Login')}}
					</span>
                <div class="row position-relative">
                    <x-admin-form-group class="col-sm-12{!! formError($errors,'email',true) !!}">
                        <x-admin-form-group-wrapper style="background-color: transparent;">
                            {!! Form::label('email', __('Email').':',['style'=>'background-color:#f5f5f5;']) !!}
                            {!! Form::text('email',old('email'),['class'=>'form-control']) !!}
                        </x-admin-form-group-wrapper>
                        {!! formError($errors,'email') !!}
                    </x-admin-form-group>
                </div>
                <div class="row position-relative">
                    <x-admin-form-group class="col-sm-12{!! formError($errors,'password',true) !!}">
                        <x-admin-form-group-wrapper style="background-color: transparent;">
                            {!! Form::label('password', __('Password').':',['style'=>'background-color:#f5f5f5;']) !!}
                            {!! Form::password('password',['class'=>'form-control','autocomplete'=>'off']) !!}
                        </x-admin-form-group-wrapper>
                        {!! formError($errors,'password') !!}
                    </x-admin-form-group>
                </div>

                <div class="flex-sb-m w-full p-t-15 p-b-20">
                    <div class="form-check">
                        <label class="form-check-label">
                            <input class="form-check-input" type="checkbox" value=""> {{__('Remember me')}}
                            <span class="form-check-sign">
									<span class="check"></span>
								</span>
                        </label>
                    </div>
                </div>
                <div class="container-login100-form-btn">
                    <button class="login100-form-btn">
                        Login
                    </button>
                </div>
            </form>
            <div class="login100-more" style="background-image: url('{{app_asset('admin/lorax/images/pages/bg-01.png')}}');">
            </div>
        </div>
    </div>
</div>
<!-- Plugins Js -->
<script src="{{app_asset('admin/lorax/js/app.min.js')}}"></script>
<script src="{{app_asset('admin/lorax/js/pages/examples/pages.js')}}"></script>
</body>


</html>
