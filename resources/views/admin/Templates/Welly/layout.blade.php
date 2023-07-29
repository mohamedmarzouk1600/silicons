<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }}</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{app_asset('admin/welly/images/favicon.png')}}">
    <link href="{{app_asset('admin/welly/vendor/jqvmap/css/jqvmap.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{app_asset('admin/welly/vendor/chartist/css/chartist.min.css')}}">
    <link href="{{app_asset('admin/welly/vendor/bootstrap-select/dist/css/bootstrap-select.min.css')}}" rel="stylesheet">
    <link href="{{app_asset('admin/welly/css/style.css')}}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
    @include('admin.Templates.css')

    @stack('css')
</head>
<body>

<div id="preloader">
    <div class="sk-three-bounce">
        <div class="sk-child sk-bounce1"></div>
        <div class="sk-child sk-bounce2"></div>
        <div class="sk-child sk-bounce3"></div>
    </div>
</div>
<div id="main-wrapper">
    <div class="nav-header">
        <a href="index.html" class="brand-logo">
            <img class="logo-abbr" src="{{app_asset('admin/welly/images/logo.png')}}" alt="">
            <img class="logo-compact" src="{{app_asset('admin/welly/images/logo-text.png')}}" alt="">
            <img class="brand-title" src="{{app_asset('admin/welly/images/logo-text.png')}}" alt="">
        </a>

        <div class="nav-control">
            <div class="hamburger">
                <span class="line"></span><span class="line"></span><span class="line"></span>
            </div>
        </div>
    </div>

    <div class="header">
        <div class="header-content">
            <nav class="navbar navbar-expand">
                <div class="collapse navbar-collapse justify-content-between">
                    <div class="header-left">
                        <div class="dashboard_bar">
                            <div class="input-group search-area d-lg-inline-flex d-none">
                                <input type="text" class="form-control" placeholder="Search here...">
                                <div class="input-group-append">
                                    <span class="input-group-text"><a href="javascript:void(0)"><i class="flaticon-381-search-2"></i></a></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <ul class="navbar-nav header-right">
                        <li class="dropdown dropdown-language nav-item">
                            <a id="dropdown-flag" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle nav-link">
                                <span class="selected-language">{{((app()->getLocale()=='en')?__('English'):__('Arabic'))}}</span>
                            </a>
                            <div aria-labelledby="dropdown-flag" class="dropdown-menu">
                                <a href="{{ChangeLanguageTo('en')}}" class="dropdown-item"><i class="flag-icon flag-icon-gb"></i> {{__('English')}}</a>
                                <a href="{{ChangeLanguageTo('ar')}}" class="dropdown-item"><i class="flag-icon flag-icon-eg"></i> {{__('Arabic')}}</a>
                            </div>
                        </li>

                        <li class="nav-item dropdown header-profile">
                            <a class="nav-link" href="javascript:void(0)" role="button" data-toggle="dropdown">
                                <div class="header-info">
                                    <span class="text-black">{{__('Hello,')}} <strong>{{auth()->user()->fullname}}</strong></span>
                                    <p class="fs-12 mb-0">{{auth()->user()->adminGroup->name}}</p>
                                </div>
                                {{--<img src="{{app_asset('admin/welly/images/profile/17.jpg')}}" width="20" alt=""/>--}}
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="{{route('admin.profile')}}" class="dropdown-item ai-icon">
                                    <svg id="icon-user1" xmlns="http://www.w3.org/2000/svg" class="text-primary" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                    <span class="ml-2">Profile </span>
                                </a>
                                <a href="{{route('admin.logout')}}" class="dropdown-item ai-icon">
                                    <svg id="icon-logout" xmlns="http://www.w3.org/2000/svg" class="text-danger" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                                    <span class="ml-2">Logout </span>
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
    <div class="deznav">
        <div class="deznav-scroll">
            <ul class="metismenu" id="menu">
                @include('admin._partial.menus',['lang'=>app()->getLocale()])
            </ul>
        </div>
    </div>

    <div class="content-body">
        <!-- row -->
        <div class="container-fluid">
            <x-admin-alert/>
            @yield('content')
        </div>
    </div>
    <div class="footer">
        <div class="copyright">
            <p>Copyright  &copy; 2017 - {{now()->format('Y')}} <a href="http://www.max-dev.com" target="_blank" class="text-bold-800 grey darken-2">MaxDev </a>, All rights reserved. </p>
        </div>
    </div>

</div>
<!-- Required vendors -->
<script src="{{app_asset('admin/welly/vendor/global/global.min.js')}}"></script>
<script src="{{app_asset('admin/welly/vendor/bootstrap-select/dist/js/bootstrap-select.min.js')}}"></script>
<script src="{{app_asset('admin/welly/js/custom.min.js')}}"></script>
<script src="{{app_asset('admin/welly/js/deznav-init.js')}}"></script>

<script src="{{app_asset('admin/welly/js/dashboard/dashboard-1.js')}}"></script>
@include('admin.Templates.scripts')

<!-- BEGIN PAGE LEVEL JS-->
@stack('scripts')
<!-- END PAGE LEVEL JS-->
</body>
</html>
