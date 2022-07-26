@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Добавление нового пользователя') }}</div>

                    <div class="card-body">
                        <div class="text-center mt-5">
                            <form method="post" action="{{ route('users.store') }}" class="login-form">
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
                                <input type="text" class="form-control mb-3" id="name" name="name" placeholder="Имя"
                                       autocomplete required>
                                <select class="form-select mb-3" aria-label="Roles" name="role" required>
                                    <option value="" selected>Выберите роль</option>
                                    @foreach($roles as $role)
                                        <option value="{{$role->code}}">{{$role->name}}</option>
                                    @endforeach
                                </select>
                                <input type="password" name="password" class="form-control" id="password" placeholder="Пароль"
                                       autocomplete required>

                                <div class="mt-3">
                                    <button class="btn btn-lg btn-success col-12">Добавить</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
