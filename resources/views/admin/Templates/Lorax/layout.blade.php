<!DOCTYPE html>
<html lang="{{app()->getLocale()}}">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>{{ $title }}</title>
    <!-- Favicon-->
    <link rel="icon" href="{{app_asset('admin/lorax/images/favicon.ico')}}" type="image/x-icon">
    <!-- Plugins Core Css -->
    <link href="{{app_asset('admin/lorax/css/app.min.css')}}" rel="stylesheet">
    <link href="{{app_asset('admin/lorax/css/form.min.css')}}" rel="stylesheet">
    <!-- full calendar -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href='{{app_asset('admin/lorax/js/bundles/fullcalendar/packages/core/main.min.css')}}' rel='stylesheet' />
    <link href='{{app_asset('admin/lorax/js/bundles/fullcalendar/packages/daygrid/main.min.css')}}' rel='stylesheet' />
    <link href='{{app_asset('admin/lorax/js/bundles/fullcalendar/packages/timegrid/main.min.css')}}' rel='stylesheet' />
    <link rel="stylesheet" type="text/css" href="{{ app_asset('admin/lorax/css/flag-icon-css/css/flag-icon.min.css') }}">
    <!-- Custom Css -->
    <link href="{{app_asset('admin/lorax/css/style.css')}}" rel="stylesheet" />
    <!-- You can choose a theme from css/styles instead of get all themes -->
    <link href="{{app_asset('admin/lorax/css/styles/all-themes.css')}}" rel="stylesheet" />
    @if(app()->getLocale() == 'ar')
    <link rel="stylesheet" href="{{app_asset('admin/lorax/js/bundles/materialize-rtl/materialize-rtl.min.css')}}" type="text/css" id="material-rtl"/>
    @endif
    <link href="{{app_asset('admin/css/custom.css')}}" rel="stylesheet" />

    @include('admin.Templates.css')
    @stack('css')
</head>

<body dir="{{((app()->getLocale()=='ar')?'rtl':'ltr')}}">
<!-- Page Loader -->
<div class="page-loader-wrapper">
    <div class="loader">
        <div class="m-t-30">
            <img class="loading-img" src="{{app_asset('admin/lorax/images/loading.png')}}" width="100" alt="Loading">
        </div>
        <p>{{__('Please wait')}}...</p>
    </div>
</div>
<!-- #END# Page Loader -->
<!-- Overlay For Sidebars -->
<div class="overlay"></div>
<!-- #END# Overlay For Sidebars -->
<!-- Top Bar -->
<nav class="navbar">
    <div class="container-fluid">
        <div class="navbar-header">
            <a href="#" onClick="return false;" class="navbar-toggle collapsed" data-bs-toggle="collapse"
               data-target="#navbar-collapse" aria-expanded="false"></a>
            <a href="#" onClick="return false;" class="bars"></a>
            <a class="navbar-brand" href="{{route('admin.dashboard')}}">
                <img src="{{app_asset('admin/lorax/images/logo.png')}}" alt="" />
                <span class="logo-name">{{config('app.name')}}</span>
            </a>
        </div>
        <div class="collapse navbar-collapse" id="navbar-collapse">
            <ul class="pull-left">
                <li>
                    <a href="#" onClick="return false;" class="sidemenu-collapse">
                        <i class="material-icons">reorder</i>
                    </a>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <!-- language select -->
                <li class="dropdown">
                    <a href="#" data-bs-toggle="dropdown" role="button" class="dropdown-toggle">
                        <i class="flag-icon flag-icon-{{((app()->getLocale()=='en')?'gb':'eg')}}"></i>
                        <span class="selected-language"></span>
                    </a>
                    <ul class="dropdown-menu language">
                        <li>
                            <ul class="menu">
                                <li>
                                    <a href="{{ChangeLanguageTo('en')}}"><i class="flag-icon flag-icon-gb"></i></a>
                                </li>
                                <li>
                                    <a href="{{ChangeLanguageTo('ar')}}"><i class="flag-icon flag-icon-eg"></i></a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <!-- #END# language select -->
                <!-- Full Screen Button -->
                <li class="fullscreen">
                    <a href="javascript:;" class="fullscreen-btn">
                        <i class="fas fa-expand"></i>
                    </a>
                </li>
                <!-- #END# Full Screen Button -->
                <!-- #START# Notifications-->
                <li class="dropdown">
                    <a href="#" onClick="return false;" class="dropdown-toggle" data-bs-toggle="dropdown"
                       role="button">
                        <i class="far fa-bell"></i>
                        <span class="label-count bg-orange"></span>
                    </a>
                    <ul class="dropdown-menu pullDown">
                        <li class="header">NOTIFICATIONS</li>
                        <li class="body">
                            <ul class="menu">
                                <li>
                                    <a href="#" onClick="return false;">
                                            <span class="table-img msg-user">
                                                <img src="{{app_asset('admin/lorax/images/user/user1.jpg')}}" alt="">
                                            </span>
                                        <span class="menu-info">
                                                <span class="menu-title">Sarah Smith</span>
                                                <span class="menu-desc">
                                                    <i class="material-icons">access_time</i> 14 mins ago
                                                </span>
                                                <span class="menu-desc">Please check your email.</span>
                                            </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" onClick="return false;">
                                            <span class="table-img msg-user">
                                                <img src="{{app_asset('admin/lorax/images/user/user2.jpg')}}" alt="">
                                            </span>
                                        <span class="menu-info">
                                                <span class="menu-title">Airi Satou</span>
                                                <span class="menu-desc">
                                                    <i class="material-icons">access_time</i> 22 mins ago
                                                </span>
                                                <span class="menu-desc">Please check your email.</span>
                                            </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" onClick="return false;">
                                            <span class="table-img msg-user">
                                                <img src="{{app_asset('admin/lorax/images/user/user3.jpg')}}" alt="">
                                            </span>
                                        <span class="menu-info">
                                                <span class="menu-title">John Doe</span>
                                                <span class="menu-desc">
                                                    <i class="material-icons">access_time</i> 3 hours ago
                                                </span>
                                                <span class="menu-desc">Please check your email.</span>
                                            </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" onClick="return false;">
                                            <span class="table-img msg-user">
                                                <img src="{{app_asset('admin/lorax/images/user/user4.jpg')}}" alt="">
                                            </span>
                                        <span class="menu-info">
                                                <span class="menu-title">Ashton Cox</span>
                                                <span class="menu-desc">
                                                    <i class="material-icons">access_time</i> 2 hours ago
                                                </span>
                                                <span class="menu-desc">Please check your email.</span>
                                            </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" onClick="return false;">
                                            <span class="table-img msg-user">
                                                <img src="{{app_asset('admin/lorax/images/user/user5.jpg')}}" alt="">
                                            </span>
                                        <span class="menu-info">
                                                <span class="menu-title">Cara Stevens</span>
                                                <span class="menu-desc">
                                                    <i class="material-icons">access_time</i> 4 hours ago
                                                </span>
                                                <span class="menu-desc">Please check your email.</span>
                                            </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" onClick="return false;">
                                            <span class="table-img msg-user">
                                                <img src="{{app_asset('admin/lorax/images/user/user6.jpg')}}" alt="">
                                            </span>
                                        <span class="menu-info">
                                                <span class="menu-title">Charde Marshall</span>
                                                <span class="menu-desc">
                                                    <i class="material-icons">access_time</i> 3 hours ago
                                                </span>
                                                <span class="menu-desc">Please check your email.</span>
                                            </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" onClick="return false;">
                                            <span class="table-img msg-user">
                                                <img src="{{app_asset('admin/lorax/images/user/user7.jpg')}}" alt="">
                                            </span>
                                        <span class="menu-info">
                                                <span class="menu-title">John Doe</span>
                                                <span class="menu-desc">
                                                    <i class="material-icons">access_time</i> Yesterday
                                                </span>
                                                <span class="menu-desc">Please check your email.</span>
                                            </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="footer">
                            <a href="#" onClick="return false;">View All Notifications</a>
                        </li>
                    </ul>
                </li>
                <!-- #END# Notifications-->
                <li class="dropdown user_profile">
                    <a href="#" onClick="return false;" class="dropdown-toggle" data-bs-toggle="dropdown"
                       role="button">
                        <img src="{{app_asset('admin/lorax/images/user.jpg')}}" width="32" height="32" alt="User">
                    </a>
                    <ul class="dropdown-menu pullDown">
                        <li class="body">
                            <ul class="user_dw_menu">
                                <li>
                                    <a href="{{route('admin.profile')}}">
                                        <i class="material-icons">person</i>{{__('User setting')}}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{route('admin.logout')}}">
                                        <i class="material-icons">power_settings_new</i>{{__('Sign out')}}
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <!-- #END# Tasks -->
            </ul>
        </div>
    </div>
</nav>
<!-- #Top Bar -->
<div>
    <!-- Left Sidebar -->
    <aside id="leftsidebar" class="sidebar">
        <!-- Menu -->
        <div class="menu">
            <ul class="list">
                <li class="sidebar-user-panel active">
                    <div class="user-panel">
                        <div class=" image">
                            <img src="{{app_asset('admin/lorax/images/usrbig.jpg')}}" class="user-img-style" alt="User Image" />
                        </div>
                    </div>
                    <div class="profile-usertitle">
                        <div class="sidebar-userpic-name"> {{auth()->user()->fullname}} </div>
                        <div class="profile-usertitle-job ">{{auth()->user()->adminGroup->name}} </div>
                    </div>
                </li>
                @include('admin._partial.menus',['lang'=>app()->getLocale()])
            </ul>
        </div>
        <!-- #Menu -->
    </aside>
    <!-- #END# Left Sidebar -->
</div>
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <x-admin-breadcrumb :title="$title" :items="$breadcrumbs??[]"/>
        </div>
        <x-admin-alert/>
        @yield('content')
    </div>
</section>
<script src="{{app_asset('admin/lorax/js/app.min.js')}}"></script>
<script src="{{app_asset('admin/lorax/js/form.min.js')}}"></script>
<!-- Custom Js -->
<script src="{{app_asset('admin/lorax/js/admin.js')}}"></script>
<!-- calendar -->
<script src='{{app_asset('admin/lorax/js/bundles/fullcalendar/packages/core/main.min.js')}}'></script>
<script src='{{app_asset('admin/lorax/js/bundles/fullcalendar/packages/interaction/main.min.js')}}'></script>
@include('admin.Templates.scripts')
<script src='{{app_asset('admin/lorax/js/lorax.js')}}'></script>

<!-- BEGIN PAGE LEVEL JS-->
@stack('scripts')
<!-- END PAGE LEVEL JS-->
</body>
</html>
