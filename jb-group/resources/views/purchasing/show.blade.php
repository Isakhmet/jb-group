@extends('layouts.main')

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
                                <form method="post" action="{{url('/purchasing/'.$purchasing->id)}}" class="login-form">
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
                                    @if(!$onlyList)
                                        <input type="text" class="form-control mb-3" name="branch"
                                               value="{{$purchasing->branches->name}}"
                                               required readonly>

                                        <textarea class="form-control mb-3" name="list" rows="3"
                                                  readonly>{{$purchasing->list}}</textarea>
                                        <input type="text" class="form-control mb-3" name="list_date"
                                               value="{{$purchasing->date}}"
                                               required readonly>
                                        <input type="text" class="form-control mb-3" name="user"
                                               value="{{$purchasing->user->name}}"
                                               required readonly>
                                    @else
                                        <p>{{$comments}}</p>
                                    @endif

                                    @foreach($productTypes as $key => $types)
                                        <h3>{{$key}}</h3>
                                        @foreach($types as $type)
                                            <div class="products">
                                                <p class="product-name">{{$type['product']->name}} {{$type['product']->description}}</p>
                                                <p class="product-count">{{$type['count']}}</p>
                                            </div>
                                        @endforeach
                                    @endforeach
                                    @if(!$onlyList)
                                        <select class="form-select mb-3" aria-label="Branches" name="status_id"
                                                required>
                                            @foreach($statuses as $key => $status)
                                                @if($key === $purchasing->status_id)
                                                    <option value="{{$key}}" selected>{{$status}}</option>
                                                @else
                                                    <option value="{{$key}}">{{$status}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <button class="btn btn-lg btn-success col-12">Обновить</button>
                                    @endif
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
