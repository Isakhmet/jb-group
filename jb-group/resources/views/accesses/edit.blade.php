@extends('layouts.main')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @can('viewAny', \App\Models\Access::class)
                <div class="card">
                    <div class="card-header">{{ __('Обновление филиала') }}</div>
                    <div class="card-body p-2">
                        <div class="text-center mt-5">
                            <form method="post" action="{{url('/accesses/'.$roleAccesses->id)}}" class="login-form">
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
                                <select class="form-select mb-3" aria-label="Roles" name="role_id" required>
                                    <option value="{{$roleAccesses->roles->id}}" selected>{{$roleAccesses->roles->name}}</option>
                                    @foreach($roles as $role)
                                        <option value="{{$role->id}}">{{$role->name}}</option>
                                    @endforeach
                                </select>
                                <select class="form-select mb-3" aria-label="Accesses" name="access_id" required>
                                    <option value="{{$roleAccesses->accesses->id}}" selected>{{$roleAccesses->accesses->description}}</option>
                                    @foreach($accesses as $access)
                                        <option value="{{$access->id}}">{{$access->description}}</option>
                                    @endforeach
                                </select>
                                <div class="mt-3">
                                    <button class="btn btn-lg btn-success col-12">Сохранить</button>
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
