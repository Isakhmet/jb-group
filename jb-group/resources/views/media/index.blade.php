@extends('layouts.main')
@section('styles')
    <link href="{{asset('fonts/material-design-icons/material-icon.css')}}" rel="stylesheet" type="text/css"/>
    <style>
        a:hover {
            text-decoration: inherit;
            color:           inherit;
        }

        .icon-link {
            display:         flex;
            flex-direction:  row;
            cursor:          pointer;
            text-decoration: inherit;
            color:           inherit;
        }

        .icon-label {
            margin-left: 5px;
        }

        .icon-holder{
            display: flex;
            flex-direction: row;
            cursor: pointer;
        }
    </style>
    <style>
        .icon-holder{
            display: flex;
            flex-direction: row;
        }
    </style>
@endsection
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">{{ __('titles.media') }}</div>
                        <div class="card-body p-2 m">
                            <div class="text-center mt-5">
                                @if ($message = Session::get('success'))
                                    <div class="alert alert-success">
                                        <p>{{ $message }}</p>
                                    </div>
                                @endif
                            </div>
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <strong>Что-то пошло не так!</strong> <br><br>
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            @foreach($files as $file)
                                <div class="mdl-cell mdl-cell--3-col mdl-cell--4-col-tablet">
                                    <div class="icon-holder">
                                        <a href="{{url('medias/show?album='.$file['name'])}}" class="icon-link">
                                            <i class="material-icons f-left">folder</i>
                                            <p class="icon-label">{{$file['name']}}</p>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
            </div>
        </div>
    </div>
@endsection
