@extends('layouts.main')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                @can('viewAny', \App\Models\Operation::class)
                <div class="card">
                    <div class="card-header">{{ __('titles.operations') }}</div>
                    {{--<form action="">--}}
                        <div class="card-body">
                            <div class="card-body">
                                <button id="buy" class="btn col-4 btn-success">Купить</button>
                                <button id="sell" class="btn col-4 btn-danger">Продать</button>
                            </div>
                            <div class="card-body">
                                <input id="currencyCodeInput" type="text" value="USD" readonly>
                            </div>
                            @include('layouts.modals.operation')
                        </div>
                        <div class="card-body p-2">
                            <div class="text-center mt-5">
                                @if ($message = Session::get('success'))
                                    <div class="alert alert-success">
                                        <p>{{ $message }}</p>
                                    </div>
                                @endif
                            </div>
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Наименования</th>
                                    <th scope="col">Курс НБ</th>
                                    <th scope="col">Курс покупки</th>
                                    <th scope="col">Курс продажи</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($currencies as $key => $currency)
                                    <tr onclick="setCurrencyCode(
                                        '{{ $currency->currency->code }}',
                                        '{{ $currency->buy_rate}}',
                                        '{{ $currency->sell_rate}}',
                                        '{{ $currency->currency->id }}'
                                        )">
                                        <th scope="row">{{$key+1}}</th>
                                        <td>{{$currency->currency->code}}</td>
                                        <td>{{$currency->nb_rate}}</td>
                                        <td>{{$currency->buy_rate}}</td>
                                        <td>{{$currency->sell_rate}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    {{--</form>--}}
                </div>
                @endcan
            </div>
        </div>
    </div>

    <script>
        $('#buy').click(function () {
            $('.overlay').css("display", "block");
            $('.sell-rate').css("display", "none");
            document.getElementById('header-title').innerText = 'Операция покупки';
            document.getElementById('actionBtn').innerText = 'Купить';
            document.getElementById('operationType').value = 'buy';
        });

        $('#sell').click(function () {
            $('.overlay').css("display", "block");
            $('.buy-rate').css("display", "none");
            document.getElementById('header-title').innerText = 'Операция продажи';
            document.getElementById('actionBtn').innerText = 'Продать';
            document.getElementById('operationType').value = 'sell';
        });

        function setCurrencyCode(code, buy, sell, currency_id) {
            document.getElementById('currencyCodeInput').value = code;
            document.getElementById('buy_rate').value = buy;
            document.getElementById('sell_rate').value = sell;
            document.getElementById('currency_id').value = currency_id;
        }
    </script>
@endsection

