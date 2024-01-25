@extends('layouts.main')

@section('styles')
    <style>
        .employee-select {
            width: 200px;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('График кассиров') }}</div>

                    <div class="card-body p-2">
                        <form method="GET" action="{{ url('schedule') }}">
                            @if(isset($year))
                                <p class="form-label ml-1">{{$year}} год</p>
                            @endif
                            <select class="form-select mb-3 w-25" name="month" id="month">
                                <option
                                    value="{{$monthKey}}">{{$monthName}}</option>
                                @foreach($months as $num => $month)
                                    <option
                                        value="{{$num}}">{{$month}}</option>
                                @endforeach
                            </select>
                            <button class="btn btn-lg btn-success col-3" type="submit">Изменить</button>
                        </form>
                    </div>

                    @if(isset($branches))
                        <form method="POST" action="{{ route('schedule-save') }}">
                            @csrf
                            <input hidden type="text" name="month" value="{{$monthKey}}">
                            @if(isset($year))
                                <input hidden type="text" name="year" value="{{$year}}">
                            @endif
                            <div style="overflow-x: scroll;" class="card-body p-2">
                                <div class="text-center mt-5">
                                    @if ($message = Session::get('success'))
                                        <div class="alert alert-success">
                                            <p>{{ $message }}</p>
                                        </div>
                                    @endif
                                </div>


                                <table class="branch table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th scope="col">Дата/Филиалы</th>
                                        @foreach($branches as $branch)
                                            <th scope="col">{{$branch['name']}}</th>
                                        @endforeach
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($dates as $day => $date)
                                        <tr>
                                            <th scope="row" style="color: #004d40; font-size: 22px">{{$day}}</th>
                                            @foreach($branches as $branch)
                                                @if(isset($date['branches']))
                                                    <td>
                                                        @if(isset($date['branches'][$branch['id']]))
                                                            @for($i=0; $i<$branch['count_cash_desk']; $i++)
                                                                <select class="form-select employee-select"
                                                                        name="employee[{{$branch['id']}}][{{$i}}][{{$day}}]"
                                                                        id="employee"
                                                                        @if(isset($date['readonly'])) disabled @endif>
                                                                    @if(isset($date['branches'][$branch['id']]['employees'][$i]))
                                                                        <option
                                                                            value="{{$date['branches'][$branch['id']]['employees'][$i]['employeeId']}}">
                                                                            {{$date['branches'][$branch['id']]['employees'][$i]['employee']}}
                                                                        </option>
                                                                    @else
                                                                        <option value=""></option>
                                                                    @endif
                                                                    @foreach($employees as $employeeId => $employee)
                                                                        <option
                                                                            value="{{$employeeId}}">{{$employee}}</option>
                                                                    @endforeach
                                                                </select>
                                                            @endfor
                                                        @else
                                                            @for($i=0; $i<$branch['count_cash_desk']; $i++)
                                                                <select class="form-select employee-select"
                                                                        name="employee[{{$branch['id']}}][{{$i}}][{{$day}}]"
                                                                        id="employee"
                                                                        @if(isset($date['readonly'])) disabled @endif>
                                                                    <option value=""></option>
                                                                    @foreach($employees as $employeeId => $employee)
                                                                        <option
                                                                            value="{{$employeeId}}">{{$employee}}</option>
                                                                    @endforeach
                                                                </select>
                                                            @endfor
                                                        @endif
                                                    </td>
                                                @else
                                                    <td>
                                                        @for($i=0; $i<$branch['count_cash_desk']; $i++)
                                                            <select class="form-select employee-select"
                                                                    name="employee[{{$branch['id']}}][{{$i}}][{{$day}}]"
                                                                    id="employee"
                                                                    @if(isset($date['readonly'])) disabled @endif>
                                                                <option value=""></option>
                                                                @foreach($employees as $employeeId => $employee)
                                                                    <option
                                                                        value="{{$employeeId}}">{{$employee}}</option>
                                                                @endforeach
                                                            </select>
                                                        @endfor
                                                    </td>
                                                @endif
                                            @endforeach
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <button class="btn btn-success" type="submit">Сохранить</button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
