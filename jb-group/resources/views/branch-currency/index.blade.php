@extends('layouts.main')

@section('content')
    <div class="card">
        <div
            class="card-header {{--text-light--}}" {{--style="background-color: #47474c;"--}}>{{ __('Остатки валют в филиалах') }}</div>
        <div class="card-body p-2"  style="overflow: auto;">
            <div class="text-center mt-5">
                @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        <p>{{ $message }}</p>
                    </div>
                @endif
            </div>
            <table class="branch table table-bordered table-striped">{{--border border-dark--}}
                <thead>
                <tr>
                    <th scope="col">Филиал/Валюты</th>
                    @foreach($currencies as $currency)
                        <th scope="col">{{$currency->code}}</th>
                    @endforeach
                </tr>
                </thead>
                <tbody>
                @foreach($branches as $key => $branch)
                    <tr>
                        <th scope="row" style="color: #004d40; font-size: 22px">{{$branch['name']}}</th>
                        @foreach($currencies as $currency)
                            @if(isset($branch['balances'][$currency->code]))
                                <td @if($branch['balances'][$currency->code]['is_limited']) style="background-color: red" @endif>
                                    <ul class="list-group">
                                        <li style="font-size: 21px; list-style-type: none;"
                                            class="money">{{$branch['balances'][$currency->code]['balance']}}</li>
                                        <li style="list-style-type: none;">{{$branch['balances'][$currency->code]['updated_at']}}</li>
                                    </ul>
                                    @if($branch['balances'][$currency->code]['change'])
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                             fill="currentColor" class="bi bi-arrow-up-short"
                                             viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                  d="M8 12a.5.5 0 0 0 .5-.5V5.707l2.146 2.147a.5.5 0 0 0 .708-.708l-3-3a.5.5 0 0 0-.708 0l-3 3a.5.5 0 1 0 .708.708L7.5 5.707V11.5a.5.5 0 0 0 .5.5z"/>
                                        </svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                             fill="currentColor" class="bi bi-arrow-down-short"
                                             viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                  d="M8 4a.5.5 0 0 1 .5.5v5.793l2.146-2.147a.5.5 0 0 1 .708.708l-3 3a.5.5 0 0 1-.708 0l-3-3a.5.5 0 1 1 .708-.708L7.5 10.293V4.5A.5.5 0 0 1 8 4z"/>
                                        </svg>
                                    @endif
                                </td>
                            @else
                                <td></td>
                            @endif
                        @endforeach
                    </tr>
                @endforeach
                <tr>
                    <th scope="row" style="color: #004d40; font-size: 22px">Общая сумма</th>
                    @foreach($currenciesSum as $code => $currencySum)
                        @if(isset($currenciesSum[$code]))
                            <td>
                                <ul class="list-group">
                                    <li style="font-size: 21px; list-style-type: none;"
                                        class="money">{{$currencySum}}</li>
                                </ul>
                            </td>
                        @else
                            <td></td>
                        @endif
                    @endforeach
                </tr>
                </tbody>
            </table>
            @can('update', \App\Models\BranchCurrency::class)
                <div class="align-content-center">
                    <h4>Обновлять каждые 30 минут(не дольше)</h4>
                    <h4>При каждой большой продаже/покупке обновление обязательно</h4>
                </div>
                <a class="btn btn-success" href="{{url('branch-currency-edit')}}" role="button">Обновить</a>
            @endcan
        </div>
    </div>
@endsection
