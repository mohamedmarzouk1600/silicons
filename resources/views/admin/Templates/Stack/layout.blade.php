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
    <link rel="stylesheet" type="text/css"
          href="{{ app_asset('dashboard/css/'.((app()->getLocale()=='ar')?'rtl':'ltr').'-app.css') }}">

    @stack('css')
    <!-- END Custom CSS-->
</head>
<body data-open="click" data-menu="vertical-menu" data-col="2-columns"
      class="vertical-layout vertical-menu 2-columns  fixed-navbar">

<!-- navbar-fixed-top-->
<nav class="header-navbar navbar navbar-with-menu navbar-fixed-top navbar-semi-dark navbar-shadow">
    <div class="navbar-wrapper">
        <div class="navbar-header">
            <ul class="nav navbar-nav">
                <li class="nav-item mobile-menu hidden-md-up float-xs-left"><a href="#"
                                                                               class="nav-link nav-menu-main menu-toggle hidden-xs"><i
                                class="ft-menu font-large-1"></i></a></li>
                <li class="nav-item"><a href="{{route('admin.dashboard')}}" class="navbar-brand">
                        <img alt="logo" src="{{ app_asset('images/logo.png') }}" style="width:75px;"
                             class="brand-logo">
                        <h2 class="brand-text">{{config('app.name')}}</h2></a></li>
                <li class="nav-item hidden-md-up float-xs-right"><a data-toggle="collapse" data-target="#navbar-mobile"
                                                                    class="nav-link open-navbar-container"><i
                                class="fa fa-ellipsis-v"></i></a></li>
            </ul>
        </div>
        <div class="navbar-container content container-fluid">
            <div id="navbar-mobile" class="collapse navbar-toggleable-sm">
                <ul class="nav navbar-nav">
                    <li class="nav-item hidden-sm-down"><a href="#"
                                                           class="nav-link nav-menu-main menu-toggle hidden-xs"><i
                                    class="ft-menu"></i></a></li>
                    <li class="nav-item hidden-sm-down"><a href="#" class="nav-link nav-link-expand"><i
                                    class="ficon ft-maximize"></i></a></li>
                    <li class="nav-item nav-search"><a href="#" class="nav-link nav-link-search"><i
                                    class="ficon ft-search"></i></a>
                        <div class="search-input">
                            <input type="text" placeholder="{{__('Search')}} ..." class="input">
                        </div>
                    </li>
                </ul>
                <ul class="nav navbar-nav float-xs-right">
                    <li class="dropdown dropdown-language nav-item">
                        <a id="dropdown-flag" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                           class="dropdown-toggle nav-link">
                            <i class="fi fi-{{((app()->getLocale()=='en')?'gb':'eg')}}"></i><span
                                    class="selected-language"></span>
                        </a>
                        <div aria-labelledby="dropdown-flag" class="dropdown-menu">
                            <a href="{{ChangeLanguageTo('en')}}" class="dropdown-item"><i
                                        class="fi fi-gb"></i> {{__('English')}}</a>
                            <a href="{{ChangeLanguageTo('ar')}}" class="dropdown-item"><i
                                        class="fi fi-eg"></i> {{__('Arabic')}}</a>
                        </div>
                    </li>

                    
                    {{--                    <li class="dropdown dropdown-notification nav-item">--}}
                    {{--                        <a href="{{url('/administrators/pharmacyprescription?status=1')}}"--}}
                    {{--                           class="nav-link nav-link-label" title="{{__('Pharmacy orders')}}"><i class="ficon ft-bell"></i>--}}
                    {{--                            <span class="tag tag-pill tag-default tag-info tag-default tag-up">--}}
                    {{--                                    {{ \MaxDev\Models\PharmacyPrescription::where('status',\MaxDev\Enums\PharmacyPrescriptionStatus::Panding)->count() }}--}}
                    {{--                                </span>--}}
                    {{--                        </a>--}}
                    {{--                    </li>--}}
                    {{--                    <li class="dropdown dropdown-notification nav-item">--}}
                    {{--                        <a href="#" data-toggle="dropdown" class="nav-link nav-link-label"><i class="ficon ft-bell"></i>--}}
                    {{--                            <span class="tag tag-pill tag-default tag-danger tag-default tag-up">5</span></a>--}}
                    {{--                        <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">--}}
                    {{--                            <li class="dropdown-menu-header">--}}
                    {{--                                <h6 class="dropdown-header m-0"><span class="grey darken-2">Notifications</span>--}}
                    {{--                                    <span class="notification-tag tag tag-default tag-danger float-xs-right m-0">5 New</span></h6>--}}
                    {{--                            </li>--}}
                    {{--                            <li class="list-group scrollable-container">--}}
                    {{--                                <a href="javascript:void(0)" class="list-group-item">--}}
                    {{--                                    <div class="media">--}}
                    {{--                                        <div class="media-left valign-middle"><i class="ft-plus-square icon-bg-circle bg-cyan"></i></div>--}}
                    {{--                                        <div class="media-body">--}}
                    {{--                                            <h6 class="media-heading">Test</h6>--}}
                    {{--                                            <p class="notification-text font-small-3 text-muted">Test</p><small>--}}
                    {{--                                                <time datetime="2015-06-11T18:29:20+08:00" class="media-meta text-muted">30 minutes ago</time></small>--}}
                    {{--                                        </div>--}}
                    {{--                                    </div>--}}
                    {{--                                </a>--}}
                    {{--                            </li>--}}
                    {{--                            <li class="dropdown-menu-footer">--}}
                    {{--                                <a href="javascript:void(0)" class="dropdown-item text-muted text-xs-center">Read all notifications</a>--}}
                    {{--                            </li>--}}
                    {{--                        </ul>--}}
                    {{--                    </li>--}}
                    {{--                    <li class="dropdown dropdown-notification nav-item"><a href="#" data-toggle="dropdown" class="nav-link nav-link-label"><i class="ficon ft-mail"></i><span class="tag tag-pill tag-default tag-warning tag-default tag-up">3</span></a>--}}
                    {{--                        <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">--}}
                    {{--                            <li class="dropdown-menu-header">--}}
                    {{--                                <h6 class="dropdown-header m-0">--}}
                    {{--                                    <span class="grey darken-2">Messages</span>--}}
                    {{--                                    <span class="notification-tag tag tag-default tag-warning float-xs-right m-0">4 New</span>--}}
                    {{--                                </h6>--}}
                    {{--                            </li>--}}
                    {{--                            <li class="list-group scrollable-container">--}}

                    {{--                                <a href="javascript:void(0)" class="list-group-item">--}}
                    {{--                                    <div class="media">--}}
                    {{--                                        <div class="media-left valign-middle">--}}
                    {{--                                            <i class="ft-plus-square icon-bg-circle bg-cyan"></i>--}}
                    {{--                                        </div>--}}
                    {{--                                        <div class="media-body">--}}
                    {{--                                            <h6 class="media-heading">Test</h6>--}}
                    {{--                                            <p class="notification-text font-small-3 text-muted">Test</p><small>--}}
                    {{--                                                <time datetime="2015-06-11T18:29:20+08:00" class="media-meta text-muted">30 minutes ago</time></small>--}}
                    {{--                                        </div>--}}
                    {{--                                    </div>--}}
                    {{--                                </a>--}}

                    {{--                            </li>--}}
                    {{--                            <li class="dropdown-menu-footer"><a href="javascript:void(0)" class="dropdown-item text-muted text-xs-center">Read all messages</a></li>--}}
                    {{--                        </ul>--}}
                    {{--                    </li>--}}
                    <li class="dropdown dropdown-user nav-item">
                        <a href="#" data-toggle="dropdown" class="dropdown-toggle nav-link dropdown-user-link">
                        <span class="avatar"
                              id="userStatus">
                            <img src="{{ app_asset('admin/images/portrait/small/avatar-s-1.png') }}"
                                 alt="avatar"><i></i>
                        </span>
                            <span class="user-name">{{auth('admin')->user()->fullname}}</span></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <!--<a href="#" class="dropdown-item"><i class="ft-unlock"></i> Test</a>-->
                            
                            <a href="{{route('admin.profile')}}" class="dropdown-item">
                                <i class="ft-power"></i>{{__('User setting')}}
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="{{route('admin.logout')}}" class="dropdown-item">
                                <i class="ft-power"></i>{{__('Sign out')}}
                            </a>


                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<!-- ////////////////////////////////////////////////////////////////////////////-->


<div data-scroll-to-active="true" class="main-menu menu-fixed menu-dark menu-accordion menu-shadow">
    <div class="main-menu-content">
        <ul id="main-menu-navigation" data-menu="menu-navigation" class="navigation navigation-main">
            <li class=" navigation-header"><span>{{__('Main menus')}}</span>
                <i data-toggle="tooltip" data-placement="right" data-original-title="General" class=" ft-minus"></i>
            </li>

            @include('admin._partial.menus',['lang'=>app()->getLocale()])

        </ul>
    </div>
</div>

<div id="app" class="app-content content container-fluid" lang="{{app()->getLocale()}}">
    <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">
            <x-admin-alert/>

            @yield('content')

        </div>
    </div>
</div>
<!-- ////////////////////////////////////////////////////////////////////////////-->


<footer class="footer footer-static footer-light navbar-border">
    <p class="clearfix blue-grey lighten-2 text-sm-center mb-0 px-2">
        <span class="float-md-left d-xs-block d-md-inline-block">Copyright  &copy;{{now()->format('Y')}} <a
                    href="https://www.linkedin.com/in/mohamed-marzouk-138158125" target="_blank" class="text-bold-800 grey darken-2">mohamed-marzouk </a>, All rights reserved. </span>
    </p>
</footer>

<!-- BEGIN VENDOR JS-->
<script src="{{ app_asset('dashboard/js/vendors.js') }}" type="text/javascript"></script>
<!-- BEGIN VENDOR JS-->
@if(app()->getLocale() == 'ar')
    <script src="{{ app_asset('admin/plugins/datepicker/js/locales/bootstrap-datetimepicker.ar.js') }}"
            type="text/javascript"></script>
@endif
<!-- BEGIN STACK JS-->
<script src="{{ app_asset('dashboard/js/stack.js') }}" type="text/javascript"></script>

<script src="{{ app_asset('dashboard/js/custom.js') }}" type="text/javascript"></script>

<script>
</script>
@include('admin.Templates.Stack.Echo')


<!-- END STACK JS-->
<!-- BEGIN PAGE LEVEL JS-->
@stack('scripts')
<!-- END PAGE LEVEL JS-->
</body>
</html>
