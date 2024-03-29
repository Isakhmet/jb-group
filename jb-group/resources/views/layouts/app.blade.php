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
    <script src="{{ URL::to('/') }}/assets/plugins/jquery-inputmask/jquery.inputmask.js"></script>
    <script src="{{ URL::to('/') }}/assets/plugins/bootstrap-inputmask/bootstrap-inputmask.min.js"></script>
    <script src="{{ URL::to('/')}}/assets/js/main.js"></script>
    <script type="text/javascript" charset="utf8"
            src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
@yield('script')
<!-- Fonts -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ URL::to('/') }}/assets/css/bootstrap/bootstrap.css" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::to('/') }}/assets/css/style.css" rel="stylesheet" type="text/css"/>

    @yield('styles')
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
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-light" href="#" id="navbarDropdown"
                                   role="button"
                                   data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ __('titles.currencies') }}
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="{{url('currencies')}}">Список</a></li>
                                    @can('create', \App\Models\Currency::class)
                                        <li><a class="dropdown-item" href="{{url('currencies/create')}}">Добавить
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endcan
                        @can('viewAny', \App\Models\Branch::class)
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-light" href="#" id="navbarDropdown"
                                   role="button"
                                   data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ __('titles.branches') }}
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="{{url('branches')}}">Список</a></li>
                                    @can('create', \App\Models\Branch::class)
                                        <li><a class="dropdown-item" href="{{url('branches/create')}}">Добавить
                                            </a></li>
                                    @endcan
                                </ul>
                            </li>
                        @endcan
                        @can('viewAny', \App\Models\BranchCurrency::class)
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-light" href="#" id="navbarDropdown"
                                   role="button"
                                   data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ __('titles.branch_currencies') }}
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="{{url('branch-currency')}}">Остатки в
                                            филиалах</a></li>
                                    <li><a class="dropdown-item" href="{{url('branch-currency?is_additional=true')}}">Остатки
                                            доп валют в
                                            филиалах</a></li>
                                    @can('create', \App\Models\BranchCurrency::class)
                                        <li><a class="dropdown-item" href="{{url('branch-currency/create')}}">Добавить
                                                валюту в филиал</a></li>
                                    @endcan
                                    @can('update', \App\Models\BranchCurrency::class)
                                        <li><a class="dropdown-item" href="{{url('branch-currency-edit')}}">Изменить
                                                остатки</a></li>
                                    @endcan
                                    @can('update', \App\Models\BranchCurrency::class)
                                        <li><a class="dropdown-item" href="{{url('branch-currency-delete')}}">Отвязать
                                                валюту от филиала</a></li>
                                    @endcan
                                </ul>
                            </li>
                        @endcan
                        @can('viewAny', \App\Models\User::class)
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-light" href="#" id="navbarDropdown"
                                   role="button"
                                   data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ __('titles.users') }}
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="{{url('users')}}">Список</a></li>
                                    @can('create', \App\Models\User::class)
                                        <li><a class="dropdown-item" href="{{url('users/create')}}">Добавить</a></li>
                                    @endcan
                                    @can('update', \App\Models\User::class)
                                        <li><a class="dropdown-item" href="{{url('add-branch')}}">Доступ к филиалам</a>
                                        </li>
                                    @endcan
                                    @can('view', \App\Models\User::class)
                                        <li><a class="dropdown-item" href="{{url('list-branch')}}">Список доступов к
                                                филиалам</a></li>
                                    @endcan
                                </ul>
                            </li>
                        @endcan
                        @can('viewAny', \App\Models\User::class)
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-light" href="#" id="navbarDropdown"
                                   role="button"
                                   data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ __('titles.accesses') }}
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="{{url('accesses')}}">Список</a></li>
                                    <li><a class="dropdown-item" href="{{url('accesses/create')}}">Добавить</a></li>
                                </ul>
                            </li>
                        @endcan
                        @can('viewAny', \App\Models\Employee::class)
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-light" href="#" id="navbarDropdown"
                                   role="button"
                                   data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ __('titles.employees') }}
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="{{url('employees')}}">Список </a></li>
                                    @can('create', \App\Models\Employee::class)
                                        <li><a class="dropdown-item" href="{{url('employees/create')}}">Добавить
                                            </a></li>
                                    @endcan
                                </ul>
                            </li>
                        @endcan
                        @can('viewAny', \App\Models\Organization::class)
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-light" href="#" id="navbarDropdown"
                                   role="button"
                                   data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ __('titles.organizations') }}
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="{{url('organizations')}}">Список</a></li>
                                    @can('create', \App\Models\Organization::class)
                                        <li><a class="dropdown-item" href="{{url('organizations/create')}}">Добавить
                                            </a></li>
                                    @endcan
                                </ul>
                            </li>
                        @endcan
                        @can('viewAny', \App\Models\Client::class)
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-light" href="#" id="navbarDropdown"
                                   role="button"
                                   data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ __('titles.clients') }}
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="{{url('clients')}}">Список</a></li>
                                    @can('create', \App\Models\Client::class)
                                        <li><a class="dropdown-item" href="{{url('clients/create')}}">Добавить
                                            </a></li>
                                    @endcan
                                </ul>
                            </li>
                        @endcan
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-light" href="#" id="navbarDropdown"
                               role="button"
                               data-bs-toggle="dropdown" aria-expanded="false">
                                {{ __('titles.media') }}
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="{{url('medias')}}">Список</a></li>
                                @can('create', \App\Models\MediaFiles::class)
                                    <li><a class="dropdown-item" href="{{url('medias/create')}}">Добавить
                                        </a></li>
                                @endcan
                            </ul>
                        </li>
                        @can('viewAny', \App\Models\PurchasingRequests::class)
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-light" href="#" id="navbarDropdown"
                                   role="button"
                                   data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ __('titles.purchasing') }}
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    @can('view', \App\Models\PurchasingRequests::class)
                                        <li>
                                            <a class="dropdown-item" href="{{url('purchasing')}}">Список</a>
                                        </li>
                                    @endcan
                                    @can('create', \App\Models\PurchasingRequests::class)
                                        <li>
                                            <a class="dropdown-item" href="{{url('purchasing/create')}}">Добавить</a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endcan
                        @can('viewAny', \App\Models\ProductType::class)
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-light" href="#" id="navbarDropdown"
                                   role="button"
                                   data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ __('titles.directory') }}
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li>
                                        <a class="dropdown-item" href="{{url('product-type-directory')}}">Типы товаров</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{url('product-directory')}}">Товары</a>
                                    </li>
                                </ul>
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
