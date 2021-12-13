<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Admin Panel</title>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
        <!--       <script src="/js/app.js"></script>-->       
        <link rel="shortcut icon" href="/favicon.png" />
        <link href="{{ asset('css/app.css') }}?v=1" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
        <!--<link href="{{ asset('css/footable.bootstrap.min.css') }}" rel="stylesheet">-->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css"/>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
        <script src="{{ asset('js/footable.min.js') }}"></script>
        <script src="{{ asset('js/admin.js') }}"></script>
        <script src="{{ asset('js/jquery.noty.packaged.min.js') }}"></script>
        <script src="{{ asset('js/info.js') }}"></script>
        <script src="{{ asset('js/jquery.alupka.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"  crossorigin="anonymous"></script>
        {!!  Info::initNotifications(".alert.alert-success") !!}
       	<?php
        //echo Info::getNotificationsJs();
        ?>
    </head>
    <body style="padding:0;">
        <div id="appz">
            @include ('layouts.admin-header')

            @if(Session::has('message'))
                <p class="alert {{ session()->get('alert-class', 'alert-info') }}">{{ session()->get('message') }}</p>
            @endif
            <div class="container">
                @yield('content')
            </div>
        </div>
        @stack('scripts')
    </body>
</html>
