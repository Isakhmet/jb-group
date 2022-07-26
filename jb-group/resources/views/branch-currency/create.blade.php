@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @can('create', \App\Models\BranchCurrency::class)
                <div class="card">
                    <div class="card-header">{{ __('Добавление валюты для филиала') }}</div>

                    <div class="card-body">
                        <div class="text-center mt-5">
                            <form method="post" action="{{ route('branch-currency.store') }}" class="login-form">
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
                                <select class="form-select mb-3" aria-label="Branch" name="branch_id" required>
                                    <option value="" selected>Выберите филиал</option>
                                    @foreach($branches as $branch)
                                        <option value="{{$branch->id}}">{{$branch->name}}</option>
                                    @endforeach
                                </select>
                                <select class="form-select mb-3" aria-label="Roles" name="currency_id" required>
                                    <option value="" selected>Выберите валюту для филиала</option>
                                    @foreach($currencies as $currency)
                                        <option value="{{$currency->id}}">{{$currency->code}}</option>
                                    @endforeach
                                </select>
                                <input type="text" class="form-control mb-3 money" id="name" name="balance"
                                       placeholder="Остаток"
                                       autocomplete required>
                                <div class="mt-3">
                                    <button class="btn btn-lg btn-success col-12">Добавить</button>
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
