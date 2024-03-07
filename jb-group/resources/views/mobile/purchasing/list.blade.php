@extends('layouts.app')

@section('styles')
    <style>
        .products {
            display: flex;
        }

        .product-count, .product-name {
            flex: 1;
        }
    </style>
@endsection
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @can('update', \App\Models\PurchasingRequests::class)
                    <div class="card">
                        <div class="card-header">{{ __('Просмотр данных') }}</div>
                        <div class="card-body p-2">
                            <div class="text-center mt-5">
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
                                <p>{{$comments}}</p>
                                @foreach($productTypes as $key => $types)
                                    <h3>{{$key}}</h3>
                                    @foreach($types as $type)
                                        <div class="products">
                                            <p class="product-name">{{$type['product']->name}} {{$type['product']->description}}</p>
                                            <p class="product-count">{{$type['count']}}</p>
                                        </div>
                                    @endforeach
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endcan
            </div>
        </div>
    </div>
    </div>
@endsection
