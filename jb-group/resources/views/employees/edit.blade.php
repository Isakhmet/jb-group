@extends('layouts.main')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @can('update', \App\Models\Employee::class)
                    <div class="card">
                        <div class="card-header">{{ __('Редактировние данных сотрудников') }}</div>
                        <div class="card-body p-2">
                            <div class="text-center mt-5">
                                <form method="post" action="{{url('/employees/'.$employee->id)}}" class="login-form">
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
                                    <input type="text" class="form-control mb-3" id="name" name="name" placeholder="ФИО"
                                           autocomplete required value="{{$employee->name}}">
                                    <input type="text" class="form-control mb-3" id="iin" name="iin" placeholder="ИИН"
                                           value="{{$employee->iin}}" minlength="12" maxlength="12">
                                    <input type="text" class="form-control mb-3" id="phone" name="phone"
                                           placeholder="Номер телефона" value="{{$employee->phone}}">
                                    <input type="text" class="form-control mb-3" id="address" name="address"
                                           placeholder="Адрес" value="{{$employee->address}}">
                                    <input type="text" class="form-control mb-3" id="position" name="position"
                                           placeholder="Должность" value="{{$employee->position}}">
                                    <input type="text" class="form-control mb-3" id="addition_phone"
                                           name="addition_phone"
                                           placeholder="Доверенный номер" value="{{$employee->addition_phone}}">
                                    <select class="form-select mb-3" aria-label="Branch" name="branch_id">
                                        <option value="{{$employee->branch->id}}"
                                                selected>{{$employee->branch->name}}</option>
                                        @foreach($branches as $branch)
                                            <option value="{{$branch->id}}">{{$branch->name}}</option>
                                        @endforeach
                                    </select>
                                    <button class="btn btn-lg btn-success col-12">Обновить</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endcan
            </div>
        </div>
    </div>
    </div>
@endsection
