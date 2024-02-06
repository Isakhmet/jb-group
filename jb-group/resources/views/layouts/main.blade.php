<!doctype html>
<html lang="en" data-bs-theme="auto">
<head>
    <script src="{{URL::to('/') }}/assets/js/sidebars/color-modes.js"></script>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    {{--
        <script src="{{ URL::to('/') }}/assets/js/bootstrap/bootstrap.js"></script>
    --}}
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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">
    <link href="{{ URL::to('/') }}/assets/js/sidebars/sidebars.css" rel="stylesheet">
</head>
<body style="background-color: #2f4f5d">
<main class="d-flex flex-nowrap text-light" style="overflow-y: auto">
    @auth
        <div class="flex-shrink-0 p-3 sidebar">
            <a href="/"
               class="d-flex align-items-center pb-3 mb-3 link-body-emphasis text-decoration-none border-bottom">
                JB-GROUP
            </a>
            <ul class="navbar-nav me-auto">
                @can('viewAny', \App\Models\Currency::class)
                    <li class="mb-1">
                        <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed"
                                data-bs-toggle="collapse" data-bs-target="#currency-collapse" aria-expanded="false">
                            {{ __('titles.currencies') }}
                        </button>
                        <div class="collapse" id="currency-collapse">
                            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                <li>
                                    <a class="link-body-emphasis d-inline-flex text-decoration-none rounded"
                                       href="{{url('currencies')}}">Список</a>
                                </li>
                                @can('create', \App\Models\Currency::class)
                                    <li>
                                        <a href="{{url('currencies/create')}}"
                                           class="link-body-emphasis d-inline-flex text-decoration-none rounded">Добавить</a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcan
                @can('viewAny', \App\Models\Branch::class)
                    <li class="mb-1">
                        <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed"
                                data-bs-toggle="collapse" data-bs-target="#branch-collapse" aria-expanded="false">
                            {{ __('titles.branches') }}
                        </button>
                        <div class="collapse" id="branch-collapse">
                            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                <li><a href="{{url('branches')}}"
                                       class="link-body-emphasis d-inline-flex text-decoration-none rounded">Список</a>
                                </li>
                                <li><a href="{{url('branches/create')}}"
                                       class="link-body-emphasis d-inline-flex text-decoration-none rounded">Добавить</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endcan
                @can('viewAny', \App\Models\BranchCurrency::class)
                    <li class="mb-1">
                        <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed"
                                data-bs-toggle="collapse" data-bs-target="#branch-currency-collapse"
                                aria-expanded="false">
                            {{ __('titles.branch_currencies') }}
                        </button>
                        <div class="collapse" id="branch-currency-collapse">
                            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                <li>
                                    <a href="{{url('branch-currency')}}"
                                       class="link-body-emphasis d-inline-flex text-decoration-none rounded">
                                        Остатки в филиалах
                                    </a>
                                </li>
                                <li><a href="{{url('branch-currency?is_additional=true')}}"
                                       class="link-body-emphasis d-inline-flex text-decoration-none rounded">
                                        Остатки доп валют в филиалах
                                    </a>
                                </li>
                                @can('create', \App\Models\BranchCurrency::class)
                                    <li><a href="{{url('branch-currency/create')}}"
                                           class="link-body-emphasis d-inline-flex text-decoration-none rounded">
                                            Добавить валюту в филиал
                                        </a>
                                    </li>
                                @endcan
                                @can('update', \App\Models\BranchCurrency::class)
                                    <li><a class="link-body-emphasis d-inline-flex text-decoration-none rounded"
                                           href="{{url('branch-currency-edit')}}">
                                            Изменить остатки
                                        </a>
                                    </li>
                                @endcan
                                @can('update', \App\Models\BranchCurrency::class)
                                    <li><a class="link-body-emphasis d-inline-flex text-decoration-none rounded"
                                           href="{{url('branch-currency-delete')}}">
                                            Отвязать валюту от филиала
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                    <li class="border-top my-3"></li>
                @endcan
                @can('viewAny', \App\Models\User::class)
                    <li class="mb-1">
                        <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed"
                                data-bs-toggle="collapse" data-bs-target="#user-collapse" aria-expanded="false">
                            {{ __('titles.users') }}
                        </button>
                        <div class="collapse" id="user-collapse">
                            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                <li>
                                    <a href="{{url('users')}}"
                                       class="link-body-emphasis d-inline-flex text-decoration-none rounded">
                                        Список
                                    </a>
                                </li>
                                @can('create', \App\Models\User::class)
                                    <li><a href="{{url('users/create')}}"
                                           class="link-body-emphasis d-inline-flex text-decoration-none rounded">
                                            Добавить
                                        </a>
                                    </li>
                                @endcan
                                @can('update', \App\Models\User::class)
                                    <li><a class="link-body-emphasis d-inline-flex text-decoration-none rounded"
                                           href="{{url('add-branch')}}">
                                            Доступ к филиалам
                                        </a>
                                    </li>
                                @endcan
                                @can('view', \App\Models\User::class)
                                    <li><a class="link-body-emphasis d-inline-flex text-decoration-none rounded"
                                           href="{{url('list-branch')}}">
                                            Список доступов к филиалам
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcan
                @can('viewAny', \App\Models\User::class)
                    <li class="mb-1">
                        <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed"
                                data-bs-toggle="collapse" data-bs-target="#access-collapse" aria-expanded="false">
                            {{ __('titles.accesses') }}
                        </button>
                        <div class="collapse" id="access-collapse">
                            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                <li><a href="{{url('accesses')}}"
                                       class="link-body-emphasis d-inline-flex text-decoration-none rounded">Список</a>
                                </li>
                                <li><a href="{{url('accesses/create')}}"
                                       class="link-body-emphasis d-inline-flex text-decoration-none rounded">Добавить</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="border-top my-3"></li>
                @endcan
                @can('viewAny', \App\Models\Employee::class)
                    <li class="mb-1">
                        <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed"
                                data-bs-toggle="collapse" data-bs-target="#employee-collapse" aria-expanded="false">
                            {{ __('titles.employees') }}
                        </button>
                        <div class="collapse" id="employee-collapse">
                            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                <li><a href="{{url('employees')}}"
                                       class="link-body-emphasis d-inline-flex text-decoration-none rounded">Список</a>
                                </li>
                                @can('create', \App\Models\Employee::class)
                                    <li><a href="{{url('employees/create')}}"
                                           class="link-body-emphasis d-inline-flex text-decoration-none rounded">Добавить</a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcan
                @can('viewAny', \App\Models\Employee::class)
                    <li class="mb-1">
                        <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed"
                                data-bs-toggle="collapse" data-bs-target="#schedule-collapse" aria-expanded="false">
                            {{ __('titles.schedule') }}
                        </button>
                        <div class="collapse" id="schedule-collapse">
                            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                <li><a href="{{url('schedule')}}"
                                       class="link-body-emphasis d-inline-flex text-decoration-none rounded">Список</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endcan
                <li class="mb-1">
                    <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed"
                            data-bs-toggle="collapse" data-bs-target="#news-collapse" aria-expanded="false">
                        {{ __('titles.news') }}
                    </button>

                    <div class="collapse" id="news-collapse">
                        <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                            <li><a href="{{url('events')}}"
                                   class="link-body-emphasis d-inline-flex text-decoration-none rounded">Новости</a>
                            </li>
                            @can('viewAny', \App\Models\User::class)
                                <li>
                                    <a href="{{url('events/create')}}"
                                       class="link-body-emphasis d-inline-flex text-decoration-none rounded">Создать</a>
                                </li>
                            @endcan
                            @can('viewAny', \App\Models\User::class)
                                <li>
                                    <a href="{{url('/show-news')}}"
                                       class="link-body-emphasis d-inline-flex text-decoration-none rounded">Редактировать</a>
                                </li>
                            @endcan
                        </ul>
                    </div>
                </li>

                @can('viewAny', \App\Models\Organization::class)
                    <li class="mb-1">
                        <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed"
                                data-bs-toggle="collapse" data-bs-target="#organizations-collapse"
                                aria-expanded="false">
                            {{ __('titles.organizations') }}
                        </button>
                        <div class="collapse" id="organizations-collapse">
                            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                <li><a href="{{url('organizations')}}"
                                       class="link-body-emphasis d-inline-flex text-decoration-none rounded">Список</a>
                                </li>
                                @can('create', \App\Models\Organization::class)
                                    <li><a href="{{url('organizations/create')}}"
                                           class="link-body-emphasis d-inline-flex text-decoration-none rounded">Добавить</a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcan
                @can('viewAny', \App\Models\Client::class)
                    <li class="mb-1">
                        <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed"
                                data-bs-toggle="collapse" data-bs-target="#clients-collapse" aria-expanded="false">
                            {{ __('titles.clients') }}
                        </button>
                        <div class="collapse" id="clients-collapse">
                            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                <li><a href="{{url('clients')}}"
                                       class="link-body-emphasis d-inline-flex text-decoration-none rounded">Список</a>
                                </li>
                                @can('create', \App\Models\Client::class)
                                    <li><a href="{{url('clients/create')}}"
                                           class="link-body-emphasis d-inline-flex text-decoration-none rounded">Добавить</a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcan
                <li class="border-top my-3"></li>
                @can('viewAny', \App\Models\PurchasingRequests::class)
                    <li class="mb-1">
                        <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed"
                                data-bs-toggle="collapse" data-bs-target="#purchasing-collapse" aria-expanded="false">
                            {{ __('titles.purchasing') }}
                        </button>
                        <div class="collapse" id="purchasing-collapse">
                            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                @can('view', \App\Models\PurchasingRequests::class)
                                    <li>
                                        <a href="{{url('purchasing')}}"
                                           class="link-body-emphasis d-inline-flex text-decoration-none rounded">
                                            Список
                                        </a>
                                    </li>
                                @endcan
                                @can('create', \App\Models\PurchasingRequests::class)
                                    <li><a href="{{url('purchasing/create')}}"
                                           class="link-body-emphasis d-inline-flex text-decoration-none rounded">
                                            Добавить
                                        </a>
                                    </li>
                                @endcan
                                @can('view', \App\Models\PurchasingRequests::class)
                                    <li>
                                        <a href="{{url('purchasing-all')}}"
                                           class="link-body-emphasis d-inline-flex text-decoration-none rounded">
                                            Полный список
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcan
                @can('viewAny', \App\Models\ProductType::class)
                    <li class="mb-1">
                        <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed"
                                data-bs-toggle="collapse" data-bs-target="#directory-collapse" aria-expanded="false">
                            {{ __('titles.directory') }}
                        </button>
                        <div class="collapse" id="directory-collapse">
                            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                <li><a href="{{url('product-type-directory')}}"
                                       class="link-body-emphasis d-inline-flex text-decoration-none rounded">Типы
                                        товаров</a>
                                </li>
                                <li><a href="{{url('product-directory')}}"
                                       class="link-body-emphasis d-inline-flex text-decoration-none rounded">Товары</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="border-top my-3"></li>
                @endcan
                <li class="mb-1">
                    <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed"
                            data-bs-toggle="collapse" data-bs-target="#media-collapse" aria-expanded="false">
                        {{ __('titles.media') }}
                    </button>
                    <div class="collapse" id="media-collapse">
                        <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                            <li><a href="{{url('medias')}}"
                                   class="link-body-emphasis d-inline-flex text-decoration-none rounded">Список</a>
                            </li>
                            @can('create', \App\Models\MediaFiles::class)
                                <li><a href="{{url('medias/create')}}"
                                       class="link-body-emphasis d-inline-flex text-decoration-none rounded">Добавить</a>
                                </li>
                            @endcan
                        </ul>
                    </div>
                </li>
                <li class="border-top my-3"></li>
                <li class="mb-1">
                    <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed"
                            data-bs-toggle="collapse" data-bs-target="#account-collapse" aria-expanded="false">
                        {{ Auth::user()->name }}
                    </button>
                    <div class="collapse" id="account-collapse">
                        <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                            <li>
                                <a href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                   class="link-body-emphasis d-inline-flex text-decoration-none rounded">
                                    {{ __('Logout') }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    @endauth
    <div class="container pt-3 text-dark">
        @yield('content')
    </div>
</main>
<script src="{{ URL::to('/') }}/assets/js/bootstrap/bootstrap.bundle.js"></script>

<script src="{{ URL::to('/') }}/assets/js/sidebars/sidebars.js"></script>
</body>
</html>
