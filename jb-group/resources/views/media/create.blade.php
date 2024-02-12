@extends('layouts.main')

@section('styles')
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
    <link href="{{asset('fonts/material-design-icons/material-icon.css')}}" rel="stylesheet" type="text/css"/>
@endsection
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Альбомы</div>
                    <div class="card-body p-2 m">
                        <div class="text-center mt-5">
                            @if ($message = Session::get('success'))
                                <div class="alert alert-success">
                                    <p>{{ $message }}</p>
                                </div>
                            @endif
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                        <div id="overlay" class="overlay">
                            <div id="modal-code" class="modal-code">
                                <div class="login-form text-center mt-5">
                                    <input type="text" class="form-control mb-3" name="album" placeholder="Названия альбома"
                                           required>
                                    <button class="btn btn-lg btn-success col-12" id="create-album">Создать</button>
                                </div>
                            </div>
                        </div>
                        @if(!isset($editable))
                            <div class="mdl-cell mdl-cell--3-col mdl-cell--4-col-tablet">
                                <div class="icon-holder icon-create">
                                    <i class="material-icons f-left">add</i>
                                    <p class="icon-label">создать альбом</p>
                                </div>
                            </div>
                            <br>
                        @endif
                        @foreach($files as $file)
                            <div class="mdl-cell mdl-cell--3-col mdl-cell--4-col-tablet">
                                <div class="icon-holder">
                                    @if(!isset($editable))
                                        <a href="{{url('medias/'.$file['name'].'/edit')}}" class="icon-link">
                                            <i class="material-icons f-left">folder</i>
                                            <p class="icon-label">{{$file['name']}}</p>
                                        </a>
                                    @else
                                        <a href="{{route('image-edit', $file['name'])}}" class="icon-link">
                                            <i class="material-icons f-left">folder</i>
                                            <p class="icon-label">{{$file['name']}}</p>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
