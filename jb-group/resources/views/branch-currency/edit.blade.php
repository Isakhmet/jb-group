@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @can('update', \App\Models\BranchCurrency::class)
                <div class="card">
                    <div class="card-header">{{ __('Обновление филиала') }}</div>
                    <div class="card-body p-2">
                        <div class="text-center mt-5">
                            <form method="post" action="{{url('/update-branch-currency')}}" class="login-form">
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
                                <select id="branch_change" class="form-select mb-3" aria-label="Branch" name="branch_id"
                                        required>
                                    <option value="" selected>Выберите филиал</option>
                                    @foreach($branches as $branch)
                                        <option value="{{$branch->id}}">{{$branch->name}}</option>
                                    @endforeach
                                </select>
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th scope="col">Валюта</th>
                                        <th scope="col">Сумма</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                                <div class="mt-3">
                                    <button class="btn btn-lg btn-success col-12" disabled="disabled">Сохранить</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endcan
            </div>
        </div>
    </div>
@endsection
