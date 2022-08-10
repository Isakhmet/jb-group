<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ URL::to('/') }}/assets/js/bootstrap/bootstrap.js"></script>
    <script src="{{ URL::to('/') }}/assets/plugins/jquery/jquery.min.js"></script>
    <script src="{{ URL::to('/') }}/assets/plugins/jquery/jquery.maskMoney.min.js"></script>
    <script src="{{ URL::to('/') }}/assets/plugins/bootstrap-inputmask/bootstrap-inputmask.min.js"></script>
    <script src="{{ URL::to('/')}}/assets/js/main.js"></script>
    <script src="{{ URL::to('/')}}/assets/js/mobile.js"></script>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ URL::to('/') }}/assets/css/bootstrap/bootstrap.css" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::to('/') }}/assets/css/style.css" rel="stylesheet" type="text/css"/>
</head>
<body style="background-color: #2f4f5d;">
<div id="app">
    <nav class="navbar navbar-expand-md navbar-light shadow-sm border border-white" style="background-color: #47474c;">
        <div class="container">
            <a class="navbar-brand text-light" href="{{ url('/branch-currency') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                @auth
                    <ul class="navbar-nav me-auto">
                        @can('viewAny', \App\Models\Currency::class)
                            <li class="nav-item">
                                <a class="nav-link text-light" href="{{url('currencies')}}"
                                   role="button">
                                    {{ __('titles.currencies') }}
                                </a>
                            </li>
                        @endcan
                        @can('viewAny', \App\Models\Branch::class)
                                <li class="nav-item">
                                    <a class="nav-link text-light" href="{{url('branches')}}"
                                       role="button">
                                        {{ __('titles.branches') }}
                                    </a>
                                </li>
                        @endcan
                        @can('viewAny', \App\Models\BranchCurrency::class)
                                <li class="nav-item">
                                    <a class="nav-link text-light" href="{{url('branch-currency')}}"
                                       role="button">
                                        {{ __('titles.branch_currencies') }}
                                    </a>
                                </li>
                        @endcan
                    </ul>
            @endauth
            <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ms-auto">
                    <!-- Authentication Links -->
                    @guest

                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle text-light" href="#" role="button"
                               data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        @yield('content')
    </main>
</div>
</body>
</html>
