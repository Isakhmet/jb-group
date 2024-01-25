@extends('layouts.main')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @can('create', \App\Models\Branch::class)
                    <div class="card">
                        <div class="card-header">{{ __('Добавление филиала') }}</div>
                        <div class="card-body p-2">
                            <div class="text-center mt-5">
                                <form method="post" action="{{ route('branches.store') }}" class="login-form">
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <strong>Что-то пошло не так!</strong> Заполните корректно данные.<br><br>
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    @csrf
                                    <input type="text" class="form-control mb-3" id="name" name="name"
                                           placeholder="Названия филиала"
                                           autocomplete required>
                                    <input type="text" class="form-control mb-3" id="phone" name="phone"
                                           placeholder="Номер телефона"
                                           autocomplete required>
                                    <input type="text" class="form-control mb-3" id="address" name="address"
                                           placeholder="Адрес"
                                           autocomplete required>
                                    <input type="text" class="form-control mb-3" id="slug" name="slug"
                                           placeholder="Имя для смс рассылки"
                                           autocomplete>
                                    <input type="number" class="form-control mb-3" id="count" name="count_cash_desk"
                                           placeholder="Колличество кассы"
                                           autocomplete>
                                    <div class="mt-3">
                                        <button class="btn btn-lg btn-success col-12">Добавить</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endcan
            </div>
        </div>
    </div>
@endsection
