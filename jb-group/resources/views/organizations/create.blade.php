@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @can('create', \App\Models\Organization::class)
                <div class="card">
                    <div class="card-header">{{ __('Добавление организаций') }}</div>
                    <div class="card-body">
                        <div class="text-center mt-5">
                            <form method="post" action="{{ route('organizations.store') }}" class="login-form">
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
                                <input type="text" class="form-control mb-3" id="name" name="name" placeholder="Названия"
                                       autocomplete required>
                                <input type="text" class="form-control mb-3" id="phone" name="contacts" placeholder="Контакты"
                                       autocomplete required>
                                <input type="text" class="form-control mb-3" name="service_type" placeholder="Вид услуги"
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
