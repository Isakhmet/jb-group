@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header {{--text-light--}}" {{--style="background-color: #47474c;"--}}>{{ __('Остатки валют в филиалах') }}</div>
                    <div class="card-body p-2" {{--style="background-color: #e9e6d3;"--}}>
                        <div class="text-center mt-5">
                            @if ($message = Session::get('success'))
                                <div class="alert alert-success">
                                    <p>{{ $message }}</p>
                                </div>
                            @endif
                        </div>
                        @csrf
                        <table class="table table-bordered table-striped">{{--border border-dark--}}
                            <thead>
                            <tr>
                                <th scope="col" class="align-middle">Филиал</th>
                                <th scope="col">
                                    <select id="currency_change" style="width: auto" class="form-select mb-3" aria-label="Currency" name="currency" required>
                                        @foreach($currencies as $currency)
                                            <option value="{{$currency->id}}" {{ $loop->first ? 'selected="selected"' : '' }}>{{$currency->code}}</option>
                                        @endforeach
                                    </select>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($branches as $key => $branch)
                                <tr>
                                    <th scope="row" style="color: #004d40; font-size: 22px">{{$branch['name']}}</th>
                                    @if(isset($branch['balances'][$currencies[0]->code]))
                                        <td @if($branch['balances'][$currencies[0]->code]['is_limited']) style="background-color: red" @endif>
                                            <ul class="list-group">
                                                <li style="font-size: 21px; list-style-type: none;" class="money">{{$branch['balances'][$currencies[0]->code]['balance']}}</li>
                                                <li style="list-style-type: none;">{{$branch['balances'][$currencies[0]->code]['updated_at']}}</li>
                                            </ul>
                                        </td>
                                    @else
                                        <td></td>
                                    @endif
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
