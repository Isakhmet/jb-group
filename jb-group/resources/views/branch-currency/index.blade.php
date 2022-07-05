@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('Остатки в филиалах') }}</div>
                    <div class="card-body">
                        <div class="text-center mt-5">
                            @if ($message = Session::get('success'))
                                <div class="alert alert-success">
                                    <p>{{ $message }}</p>
                                </div>
                            @endif
                        </div>
                        <table class="table table-bordered">
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
                                    <th scope="row">{{$branch['name']}}</th>
                                    @foreach($currencies as $currency)
                                        @if(isset($branch['balances'][$currency->code]))
                                            <td @if($branch['balances'][$currency->code]['is_limited']) style="background-color: red" @endif>
                                                <ul class="list-group">
                                                    <li style="font-size: 25px; list-style-type: none;" class="money">{{$branch['balances'][$currency->code]['balance']}}</li>
                                                    <li style="list-style-type: none;">{{$branch['balances'][$currency->code]['updated_at']}}</li>
                                                </ul>
                                            </td>
                                        @else
                                            <td></td>
                                        @endif
                                    @endforeach
                                </tr>
                            @endforeach
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
            </div>
        </div>
    </div>
@endsection
