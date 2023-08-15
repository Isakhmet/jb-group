@extends('layouts.app')
@section('styles')
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
    <style>
        .icon-holder{
            display: flex;
            flex-direction: row;
        }
        .img-thumbnail:hover {
            width: 250px;
            height: 250px;
        }
        .card-image {
            display: flex;
            flex-direction: row;
        }
        .image {
            border-radius: 20px;
            overflow: hidden;
            width: 150px;
            height: 150px;
            position: relative;
            display: block;
            z-index: 10;
            margin: 0 30px;
        }

        .image:hover {
            width: 250px;
            height: 250px;
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
                            {{--<div class="card-image">
                                <div class="image">
                                    <img src="https://mdbcdn.b-cdn.net/img/new/slides/041.webp" width="200px" height="150px" alt="..." class="img-thumbnail">
                                </div>
                                <div class="image">
                                    <img src="/images/rules.jpeg" width="200px" height="150px" class="img-thumbnail d-block" alt="..." >
                                </div>
                                <div class="image">
                                    <img src="https://mdbcdn.b-cdn.net/img/new/slides/041.webp" width="200px" height="150px" alt="..." class="img-thumbnail">
                                </div>
                            </div>--}}
                            {{--<div class="mdl-cell mdl-cell--3-col mdl-cell--4-col-tablet">
                                <div class="icon-holder">
                                    <i class="material-icons f-left">folder_open</i>
                                    <p class="icon-label">все фотки</p>
                                </div>
                            </div>--}}

                        {{--    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                                Launch demo modal
                            </button>

                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="bd-example">
                                                <div id="carouselExampleCaptions" class="carousel slide" data-ride="carousel">
                                                    <ol class="carousel-indicators">
                                                        <li data-target="#carouselExampleCaptions" data-slide-to="0" class="active"></li>
                                                        <li data-target="#carouselExampleCaptions" data-slide-to="1"></li>
                                                        <li data-target="#carouselExampleCaptions" data-slide-to="2"></li>
                                                    </ol>
                                                    <div class="carousel-inner">
                                                        <div class="carousel-item active">
                                                            <img src="/images/download.jpeg" class="d-block w-100" alt="...">
                                                            <div class="carousel-caption d-none d-md-block">
                                                                <h5>First slide label</h5>
                                                                <p>Nulla vitae elit libero, a pharetra augue mollis interdum.</p>
                                                            </div>
                                                        </div>
                                                        <div class="carousel-item">
                                                            <img src="/images/rules.jpeg" class="d-block w-100" alt="...">
                                                            <div class="carousel-caption d-none d-md-block">
                                                                <h5>Second slide label</h5>
                                                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <a class="carousel-control-prev" href="#carouselExampleCaptions" role="button" data-slide="prev">
                                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                        <span class="sr-only">Previous</span>
                                                    </a>
                                                    <a class="carousel-control-next" href="#carouselExampleCaptions" role="button" data-slide="next">
                                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                        <span class="sr-only">Next</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        --}}
                        </div>
                    </div>
            </div>
        </div>
    </div>
@endsection
