<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>{{ $title }}</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{app_asset('admin/welly/images/favicon.png')}}">
    <link href="{{app_asset('admin/welly/css/style.css')}}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
    @yield('header')
</head>

<body class="h-100">
<div class="authincation h-100">
    <div class="container h-100">
        <div class="row justify-content-center h-100 align-items-center">
            <div class="col-md-6">
                <div class="authincation-content">
                    <div class="row no-gutters">
                        <div class="col-xl-12">
                            <div class="auth-form">
                                <div class="text-center mb-3">
                                    <a href="{{route('admin.dashboard')}}"><img src="{{app_asset('admin/welly/images/logo-full.png')}}" alt=""></a>
                                </div>
                                <h4 class="text-center mb-4 text-white">{{__('Login')}}</h4>
                                <form method="post" action="{{ route('admin.login') }}">
                                    {{ csrf_field() }}
                                    <div class="form-group {{ $errors->has('email') ? ' input-danger' : '' }}">
                                        <label class="mb-1 text-white"><strong>Email</strong></label>
                                        <input name="email" type="email" class="form-control" value="{{old('email')}}" placeholder="{{__('Enter Email')}}">
                                        @if($errors->has('email'))
                                            <p class="text-xs-right">
                                                <small class="danger text-muted">
                                                    @foreach($errors->get('email') as $error)
                                                        {{$error}} <br />
                                                    @endforeach
                                                </small>
                                            </p>
                                        @endif
                                    </div>
                                    <div class="form-group {{$errors->has('password') ? ' input-danger' : ''}}">
                                        <label class="mb-1 text-white"><strong>Password</strong></label>
                                        <input name="password" type="password" class="form-control" placeholder="{{__('Enter Your Password')}}">
                                        @if($errors->has('password'))
                                            <p class="text-xs-right">
                                                <small class="danger text-muted">
                                                    @foreach($errors->get('password') as $error)
                                                        {{$error}} <br />
                                                    @endforeach
                                                </small>
                                            </p>
                                        @endif
                                    </div>
                                    <div class="form-row d-flex justify-content-between mt-4 mb-2">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox ml-1 text-white">
                                                <input type="checkbox" name="remember" class="custom-control-input" id="basic_checkbox_1">
                                                <label class="custom-control-label" for="basic_checkbox_1">{{__('Remember Me')}}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn bg-white text-primary btn-block">{{__('Login')}}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!--**********************************
    Scripts
***********************************-->
<!-- Required vendors -->
<script src="{{app_asset('admin/welly/vendor/global/global.min.js')}}"></script>
<script src="{{app_asset('admin/welly/vendor/bootstrap-select/dist/js/bootstrap-select.min.js')}}"></script>
<script src="{{app_asset('admin/welly/js/custom.min.js')}}"></script>
<script src="{{app_asset('admin/welly/js/deznav-init.js')}}"></script>

</body>

</html>
