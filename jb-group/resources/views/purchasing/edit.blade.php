@extends('layouts.app')

@section('script')
    <script src="{{ URL::to('/') }}/assets/plugins/flatpicker/js/flatpicker.min.js"></script>
    <script src="{{ URL::to('/') }}/assets/js/pages/datepicker/datetimepicker.js"></script>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/plugins/flatpicker/css/flatpickr.min.css"/>
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
