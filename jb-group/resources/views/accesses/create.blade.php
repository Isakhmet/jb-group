@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @can('viewAny', \App\Models\Access::class)
                <div class="card">
                    <div class="card-header">{{ __('Добавление валюты для филиала') }}</div>

                    <div class="card-body">
                        <div class="text-center mt-5">
                            <form method="post" action="{{ route('accesses.store') }}" class="login-form">
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
                                <select class="form-select mb-3" aria-label="Roles" name="role_id" required>
                                    <option value="" selected>Выберите роль</option>
                                    @foreach($roles as $role)
                                        <option value="{{$role->id}}">{{$role->name}}</option>
                                    @endforeach
                                </select>
                                <select class="form-select mb-3" aria-label="Accesses" name="access_id" required>
                                    <option value="" selected>Выберите доступ</option>
                                    @foreach($accesses as $access)
                                        <option value="{{$access->id}}">{{$access->description}}</option>
                                    @endforeach
                                </select>
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
