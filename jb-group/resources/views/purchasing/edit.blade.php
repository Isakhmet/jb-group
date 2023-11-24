@extends('layouts.main')

@section('script')
    <script src="{{ URL::to('/') }}/assets/plugins/flatpicker/js/flatpicker.min.js"></script>
    <script src="{{ URL::to('/') }}/assets/js/pages/datepicker/datetimepicker.js"></script>
    <script>
        function incrementCounter(name) {
            let count = $('.'+name).val()

            count++;
            $('.'+name).val(count);
        }

        function decrementCounter(name) {
            let count = $('.'+name).val()

            if (count > 0) {
                count--;
                $('.'+name).val(count);
            }
        }
    </script>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/plugins/flatpicker/css/flatpickr.min.css"/>
    <style>
        .item-list {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            list-style-type: none;
            padding: 0;
        }

        .item-list li {
            display: flex;
            align-items: center;
            margin: 10px 0;
            width: 100%;
        }

        .item-name, .counter {
            flex: 1;
            padding: 5px;
        }

        .counter-button {
            background-color: #157347;
            color: #fff;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }

        .counter {
            padding: 5px;
        }
        span {cursor:pointer; }

        .counter-item {
            height:28px;
            width: 59px;
            text-align: center;
            font-size: 20px;
            border:1px solid #ddd;
            border-radius:4px;
            display: inline-block;
            vertical-align: middle;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @can('update', \App\Models\PurchasingRequests::class)
                    <div class="card">
                        <div class="card-header">{{ __('Редактировние данных') }}</div>
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
                                        <select class="form-select mb-3" aria-label="Branches" name="branch_id" required>
                                            @foreach($branches as $branch)
                                                @if($branch === $purchasing->branches->id)
                                                    <option value="{{$branch->id}}" selected>{{$branch->name}}</option>
                                                @else
                                                    <option value="{{$branch->id}}">{{$branch->name}}</option>
                                                @endif
                                            @endforeach
                                        </select>

                                        @foreach($productTypes as $name => $type)
                                            <p>{{$name}}</p>
                                            <ul class="item-list">
                                                @foreach($type as $product)
                                                    <li>
                                                        <span class="item-name">{{$product['product']->name}} {{$product['product']->description}}</span>
                                                        <div class="counter">
                                                            <span class="counter-button" onclick="decrementCounter('item_{{$product['product']->id}}')">-</span>
                                                            <input class="counter-item item_{{$product['product']->id}}" name="items[{{$product['product']->id}}]" type="text" value="{{$product['count']}}"/>
                                                            <span class="counter-button" onclick="incrementCounter('item_{{$product['product']->id}}')">+</span>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endforeach
                                        <textarea class="form-control mb-3" name="list" rows="3">{{$purchasing->list}}</textarea>
                                        <input type="text" class="form-control mb-3" name="list_date" value="{{$purchasing->date}}"
                                               required id="date1">
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
