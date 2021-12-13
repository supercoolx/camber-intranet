<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script> --}}

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    @stack('stylesheets')

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <!-- Styles -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link href="{{ asset('css/app.css') }}?v=1" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"/>
    <link rel="shortcut icon" href="/favicon.png" />
    <script src="{{ asset('js/jquery.noty.packaged.min.js') }}"></script>
    <script src="{{ asset('js/info.js') }}"></script>
    <script src="{{ asset('js/jquery.alupka.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"  crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.js"></script>
    {!!  Info::initNotifications(".alert.alert-success") !!}
</head>
<body>
    <div id="appz">
        <header class="fixed-top py-0">
            <nav class="navbar navbar-expand-lg navbar-dark w-100">
                <a href="/" class="logo mr-md-5"><img src="{{ asset('img/logo_camber.svg') }}"></a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav w-100">
                        @auth
                        <li class="nav-item active order-1">
                            <a class="btn btn-link text-light h-100 d-flex align-items-center justify-content-center {{ (request()->is('home')) ? 'active' : '' }}" href="{{ route('home') }}">
                                <i class="fas fa-home mr-1"></i>
                                Home
                            </a>
                        </li>
                        <li class="nav-item order-2">
                            <a class="btn btn-link text-light h-100 d-flex align-items-center justify-content-center {{ (request()->is(route('agent.dashboard'))) ? 'active' : '' }}" href="{{ route('agent.dashboard') }}">
                                <i class="fas fa-th-list mr-1"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item ml-md-auto order-6 order-md-3 text-center">
                            <span class="welcome justify-content-center">Welcome {{ auth()->user()->name }}!</span>
                        </li>
                        <li class="nav-item order-4">
                            <a class="btn btn-link text-light h-100 d-flex align-items-center justify-content-center" href="{{ route('profile') }}">
                                <i class="fas fa-user-cog mr-1"></i>
                                Settings
                            </a>
                        </li>
                        <li class="nav-item order-5">
                            <a class="btn btn-link text-light h-100 d-flex align-items-center justify-content-center" href="{{ route('logout') }}" onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt mr-1"></i> {{ __('Logout') }}</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                        @endauth
                    </ul>

                </div>
            </nav>

        </header>
          
        @if(Session::has('message'))
                <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
            @endif
        <main>
            @yield('content')
        </main>
    </div>
    <a href="#" class="scrollup" style="display: inline;"><i class="fas fa-chevron-circle-up"></i></a>
    <script src="{{ asset('js/front.js') }}"></script>

    @stack('scripts')
</body>
</html>
