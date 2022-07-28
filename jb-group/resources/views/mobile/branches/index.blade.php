@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @can('viewAny', \App\Models\Branch::class)
                <div class="card">
                    <div class="card-header">{{ __('Филиалы') }}</div>
                    <div class="card-body p-2">
                        <div class="text-center mt-5">
                            @if ($message = Session::get('success'))
                                <div class="alert alert-success">
                                    <p>{{ $message }}</p>
                                </div>
                            @endif
                        </div>
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th scope="col">Названия</th>
                                <th scope="col">Номер телефона</th>
                                <th scope="col">Адрес</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($branches as $key => $branch)
                            <tr>
                                <td>{{$branch->name}}</td>
                                <td>{{$branch->phone}}</td>
                                <td>{{$branch->address}}</td>
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
