@extends('layouts.main')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                @can('viewAny', \App\Models\Operation::class)
                <div class="card">
                    <div class="card-header">{{ __('titles.operations') }}</div>
                    <div class="card-body p-2">
                        <div class="text-center mt-5">
                            @if ($message = Session::get('success'))
                                <div class="alert alert-success">
                                    <p>{{ $message }}</p>
                                </div>
                            @endif
                        </div>
                        <div class="text-center mt-5">
                                <div class="alert alert-success">
                                    <p>Прибыль за период:</p>
                                    <p>{{ $profit }}тг</p>
                                </div>
                        </div>
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Тип операций</th>
                                <th scope="col">Валюта</th>
                                <th scope="col">Сумма</th>
                                <th scope="col">Курс</th>
                                <th scope="col">Сумма в Тенге</th>
                                <th scope="col">Дата создания</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($operations as $key => $operation)
                            <tr>
                                <th scope="row">{{$key+1}}</th>
                                <td>{{$operation->type === 'buy' ? 'Продано' : 'Куплено'}}</td>
                                <td>{{$operation->currency->code}}</td>
                                <td>{{$operation->amount}}</td>
                                <td>{{$operation->rate}}</td>
                                <td>{{$operation->amount*$operation->rate}}</td>
                                <td>{{$operation->created_at}}</td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endcan
            </div>
        </div>
    </div>
@endsection
