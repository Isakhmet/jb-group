@extends('layouts.app')

@section('script')
    {{--<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
            integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"
            integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"
            integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
            crossorigin="anonymous"></script>--}}
    {{--<script src="{{ URL::to('/')}}/assets/js/main.js"></script>--}}
@endsection

@section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="{{ URL::to('/') }}/fonts/material-design-icons/material-icon.css" rel="stylesheet" type="text/css"/>
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
                        <div class="mdl-cell mdl-cell--3-col mdl-cell--4-col-tablet">
                            <div class="icon-holder icon-create">
                                <i class="material-icons f-left">add</i>
                                <p class="icon-label">создать альбом</p>
                            </div>
                        </div>
                        <br>
                        @foreach($files as $file)
                            <div class="mdl-cell mdl-cell--3-col mdl-cell--4-col-tablet">
                                <div class="icon-holder">
                                    <a href="{{url('medias/'.$file['name'].'/edit')}}" class="icon-link">
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
