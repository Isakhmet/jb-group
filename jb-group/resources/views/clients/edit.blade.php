@extends('layouts.main')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @can('update', \App\Models\Client::class)
                    <div class="card">
                        <div class="card-header">{{ __('Редактировние данных клиента') }}</div>
                        <div class="card-body p-2">
                            <div class="text-center mt-5">
                                <form method="post" action="{{url('/clients/'.$client->id)}}" class="login-form">
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
                                           autocomplete required value="{{$client->name}}">
                                    <input type="text" class="form-control mb-3" id="iin" name="iin" placeholder="ИИН"
                                           value="{{$client->iin}}" minlength="12" maxlength="12">
                                    <input type="text" class="form-control mb-3" id="phone" name="phone"
                                           placeholder="Номер телефона" value="{{$client->phone}}">
                                    <input type="text" class="form-control mb-3" id="comment" name="comment"
                                           placeholder="Комментарий" value="{{$client->comment}}">
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
