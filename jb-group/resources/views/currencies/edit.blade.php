@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @can('update', \App\Models\Currency::class)
                <div class="card">
                    <div class="card-header">{{ __('Обновление валюты') }}</div>

                    <div class="card-body p-2">
                        <div class="text-center mt-5">
                            <form method="post" action="{{url('/currencies/'.$currency->id)}}" class="login-form">
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
                                    <input type="text" class="form-control mb-3" id="name" name="code" placeholder="Код валюты"
                                           autocomplete required value="{{$currency->code}}">
                                    <input type="text" class="form-control mb-3" id="name" name="description" placeholder="Описание"
                                           autocomplete required value="{{$currency->description}}">
                                    <input type="text" name="limit" class="form-control" id="limit" placeholder="Лимит"
                                           autocomplete required value="{{$currency->limit}}">
                                    <select name="is_additional" class="form-control" id="is_additional" style="margin-top: 15px;">
                                        <option value="0" {{$currency->is_additional === false ? 'selected' : ''}}>Нет</option>
                                        <option value="1" {{$currency->is_additional === true ? 'selected' : ''}}>Да</option>
                                    </select>
                                <div class="mt-3">
                                    <button class="btn btn-lg btn-success col-12">Обновить</button>
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
