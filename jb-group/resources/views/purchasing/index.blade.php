@extends('layouts.main')

@section('styles')
    <style>
        tr:hover {
            cursor: pointer;
        }
        .status-new {
           color: #198754;
        }
    </style>
@endsection
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                @can('viewAny', \App\Models\PurchasingRequests::class)
                <div class="card">
                    <div class="card-header">{{ __('titles.purchasing') }}</div>
                    <div class="card-body p-2">
                        <div class="text-center mt-5">
                            @if ($message = Session::get('success'))
                                <div class="alert alert-success">
                                    <p>{{ $message }}</p>
                                </div>
                            @endif
                        </div>
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Статус</th>
                                <th scope="col">Филиал</th>
                                <th scope="col">Менеджер</th>
                                <th scope="col">Дата</th>
                                <th scope="col">Коментарий</th>
                                <th scope="col">Действия</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($purchasingRequests as $key => $purchasing)
                            <tr onclick="window.location='{{url('/purchasing/'.$purchasing->id)}}'">
                                <th scope="row">{{$key+1}}</th>
                                <td class="{{$purchasing->status()->name === 'new' ? 'status-new' : ''}}">
                                    {{$purchasing->status()->description}}
                                </td>
                                <td>{{$purchasing->branches->name}}</td>
                                <td>{{$purchasing->user->name}}</td>
                                <td>{{$purchasing->date}}</td>
                                <td>{{$purchasing->list}}</td>
                                <td>
                                    <div style="display: inline-flex;">
                                            <div>
                                                <a href="{{url('/purchasing/'.$purchasing->id).'/edit'}}"
                                                   class="btn btn-success btn-xs">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                                        <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                                                    </svg>
                                                </a>
                                            </div>
                                    </div>
                                </td>
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
