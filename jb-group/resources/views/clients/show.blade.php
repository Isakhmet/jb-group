@extends('layouts.main')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @can('update', \App\Models\Client::class)
                    <div class="card">
                        <div class="card-header">{{ __('Просмотр данных клиента') }}</div>
                        <div class="card-body p-2">
                            <div class="login-form text-center mt-5">
                                @csrf
                                <input type="text" class="form-control mb-3" id="name" name="name" placeholder="ФИО"
                                       autocomplete readonly value="{{$client->name}}">
                                <input type="text" class="form-control mb-3" id="iin" name="iin" placeholder="ИИН"
                                       readonly value="{{$client->iin}}" minlength="12" maxlength="12">
                                <input type="text" class="form-control mb-3" id="phone" name="phone"
                                       readonly placeholder="Номер телефона" value="{{$client->phone}}">
                                <input type="text" class="form-control mb-3" id="comment" name="comment"
                                       readonly placeholder="Комментарий" value="{{$client->comment}}">
                                <button class="btn btn-lg btn-success col-12 notify">Отправить смс</button>
                            </div>
                        </div>
                    </div>
                    <div id="overlay" class="overlay">
                        <div id="modal-code" class="modal-code">
                            <div class="login-form text-center mt-5">
                                <input type="text" class="form-control mb-3" name="code" placeholder="СМС код"
                                        required>
                                <input id="code" type="hidden" value="">
                                <button class="btn btn-lg btn-success col-12 check-code">Проверить код</button>
                            </div>
                        </div>
                    </div>
                @endcan
            </div>
        </div>
    </div>
    </div>
@endsection
