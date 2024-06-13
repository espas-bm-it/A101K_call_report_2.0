<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Call Report - ESPAS</title>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <!--Favicon-->
    <link href="img/favicon-32x32.png" rel="icon">
    <!--Datatables yajra -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" defer></script>
    {{-- Bootstrap not in use --}}
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.1/css/bootstrap.min.css" rel="stylesheet"> -->
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.1/js/bootstrap.min.js" defer></script> --}}
    {{-- Moment.js and Daterangepicker --}}
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js" defer></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js" defer></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- DataTables Language File CDN -->
    <script src="//cdn.datatables.net/plug-ins/1.10.24/i18n/German.json"></script>
    <script src="{{url('js/app.js')}}" defer></script>
    <!-- Font awesome -->
    <script src="https://kit.fontawesome.com/bb2d335bd6.js" crossorigin="anonymous"></script>
    <!-- Scripts --> @vite(['resources/sass/app.scss', 'resources/js/app.js'])
     {{-- Bootstrap --}}
    <link href=" https://cdn.jsdelivr.net/npm/bootswatch@5.3.3/dist/united/bootstrap.min.css " rel="stylesheet">
    <!-- Custom CSS Espas Design -->
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet" type="text/css">
  </head>
  <body>
    <div id="app">
      <nav class="navbar navbar-expand-md navbar-light shadow-sm">
        <div class="container">
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav me-auto">
              <a class="navbar-brand" href="{{ url('/home') }}">
                <img src="{{ asset('img/espas_logo.svg') }}" alt="ESPAS Logo" width="120" height="54">
              </a>
            </ul>
            <p class="text-center fw-bold fs-5 text-light mt-3">Telefonservice Call Report</p>
            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ms-auto">
              <!-- Authentication Links --> @guest @if (Route::has('login')) <li class="nav-item">
                <a class="nav-link link-light" href="{{ route('login') }}">{{ __('Einloggen') }}</a>
              </li> @endif @if (Route::has('register')) <li class="nav-item">
                <a class="nav-link link-light" href="{{ route('register') }}">{{ __('Registrieren') }}</a>
              </li> @endif @else <li class="nav-item dropdown">
                <a id="navbarDropdown" class="nav-link link-light dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                  {{ Auth::user()->name }}
                </a>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                  <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                    {{ __('Ausloggen') }}
                  </a>
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none"> @csrf </form>
                </div>
              </li> @endguest
            </ul>
          </div>
        </div>
      </nav>
      <div id="es-balken-1" class="d-flex flex-row" style="position: relative;">
        <div id="es-balken-2" style="flex-grow: 2;"></div>
        <div id="es-balken-3" style="flex-grow: 2;"></div>
        <div id="es-balken-4" style="flex-grow: 1;"></div>
        <div id="es-balken-5" style="flex-grow: 1;"></div>
      </div>
      <main class="container"> @yield('content') </main>
    </div> @stack('scripts')
  </body>
</html>