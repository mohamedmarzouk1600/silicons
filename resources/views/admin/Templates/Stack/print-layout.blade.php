<!DOCTYPE html>
<html lang="{{app()->getLocale()}}" data-textdirection="{{((app()->getLocale()=='ar')?'rtl':'ltr')}}" class="loading">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="author" content="app">
    <link rel="stylesheet" type="text/css" href="{{ app_asset('dashboard/css/app.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ app_asset('dashboard/css/'.((app()->getLocale()=='ar')?'rtl':'ltr').'-app.css') }}">
    <link href="{{app_asset('admin/css/custom.css')}}" rel="stylesheet" />
    <link href="{{ app_asset('admin/css/print/pdf-'.app()->getLocale().'.css') }}" rel="stylesheet" />

    @stack('css')
    <!-- END Custom CSS-->
</head>
<body>
 @yield('content')
</body>
</html>
