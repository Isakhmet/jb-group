@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Обновление пользователя') }}</div>

                    <div class="card-body p-2">
                        <div class="text-center mt-5">
                            <form method="post" action="{{url('/users/'.$user->id)}}" class="login-form">
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
                                @method('PUT')
                                <input type="text" class="form-control" id="name" name="name" placeholder="Имя"
                                       autocomplete required value="{{$user->name}}">
                                <select class="form-select" aria-label="Roles" name="role" required>
                                    <option value="{{$user->roles?->code}}" selected>{{$user->roles?->name}}</option>
                                    @foreach($roles as $role)
                                        <option value="{{$role->code}}">{{$role->name}}</option>
                                    @endforeach
                                </select>
                                <input type="password" name="password" class="form-control" id="password"
                                       placeholder="Пароль"
                                       autocomplete>

                                <div class="mt-3">
                                    <button class="btn btn-lg btn-success col-12">Обновить</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
