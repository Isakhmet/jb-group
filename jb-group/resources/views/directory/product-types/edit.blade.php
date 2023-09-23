@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @can('viewAny', \App\Models\ProductType::class)
                <div class="card">
                    <div class="card-header">{{ __('Обновление типов товаров') }}</div>
                    <div class="card-body p-2">
                        <div class="text-center mt-5">
                            <form method="post" action="{{url('/product-type-directory/'.$productType->id)}}" class="login-form">
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
                                    <input type="text" class="form-control mb-3" id="name" name="name"
                                           placeholder="Названия типа товаров"
                                           autocomplete required value="{{$productType->name}}">
                                    <input type="text" class="form-control mb-3" id="description" name="description"
                                           placeholder="описание" value="{{$productType->description}}">
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
