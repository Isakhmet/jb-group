@extends('layouts.main')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @can('update', \App\Models\Branch::class)
                    <div class="card">
                        <div class="card-header">{{ __('Обновление филиала') }}</div>
                        <div class="card-body p-2">
                            <div class="text-center mt-5">
                                <form method="post" action="{{url('/branches/'.$branch->id)}}" class="login-form">
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
                                    <label for="name" class="col-form-label fw-bold">Названия
                                        филиала</label>
                                    <input type="text" class="form-control mb-3" id="name" name="name"
                                           autocomplete required value="{{$branch->name}}">
                                    <label for="phone" class="col-form-label fw-bold ">Номер телефона</label>
                                    <input type="text" class="form-control mb-3" id="phone" name="phone"
                                           autocomplete required value="{{$branch->phone}}">
                                    <label for="address" class="col-form-label fw-bold ">Адрес</label>
                                    <input type="text" class="form-control mb-3" id="address" name="address"
                                           autocomplete required value="{{$branch->address}}">
                                    <label for="slug" class="col-form-label fw-bold ">Имя для смс рассылки</label>
                                    <input type="text" class="form-control mb-3" id="slug" name="slug"
                                           autocomplete required value="{{$branch->slug}}">
                                    <label for="count" class="col-form-label fw-bold ">Колличество кассы</label>
                                    <input type="number" class="form-control mb-3" id="count" name="count_cash_desk"
                                           value="{{$branch->count_cash_desk}}" autocomplete>
                                    <button class="btn btn-lg btn-success col-12">Обновить</button>
                            </div>
                            </form>
                        </div>
                    </div>
                @endcan
            </div>
        </div>
    </div>
    </div>
@endsection
