@extends('layouts.main')

@section('script')
    <script src="{{ URL::to('/') }}/assets/plugins/dropzone/dropzone.js"></script>
    <script src="{{ URL::to('/') }}/assets/plugins/dropzone/dropzone-call.js"></script>

@endsection

@section('styles')
    <link href="{{ URL::to('/') }}/assets/plugins/dropzone/dropzone.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/plugins/material/material.min.css">
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/css/material_style.css">
    <style>
        .icon-link:hover {
            text-decoration: inherit;
            color: inherit;
        }

        .icon-link {
            display: flex;
            flex-direction: row;
            cursor: pointer;
            text-decoration: inherit;
            color: inherit;
            font-size: large;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="card">
                <div class="card-header"><a href="{{url('medias/create')}}" class="icon-link">Альбомы</a></div>
                <div class="card-body row">
                    <section class="col-12 mb-3">
                        @if(isset($editable))
                            <form action="{{route('deleteByOne')}}">
                                <input type="hidden" name="album" value="{{$album}}">
                                <div class="row">
                                    @foreach($images as $image)
                                        <div class="col-lg-4 col-md-12 mb-4 mb-lg-3">
                                            <div class="bg-image hover-overlay ripple shadow-1-strong rounded"
                                                 data-ripple-color="light">
                                                <input name="images[]" type="checkbox" class="checkbox"
                                                       value="{{$image->name}}">
                                                @if(str_contains($image->format, 'video'))
                                                    <video class="w-100" style="height: 400px" controls>
                                                        <source src="/images/{{$image->album->name}}/{{$image->name}}"
                                                                type="video/mp4">
                                                        Your browser does not support the video tag.
                                                    </video>
                                                @else
                                                    <img src="/images/{{$image->album->name}}/{{$image->name}}"
                                                         class="w-100" style="max-height: 550px"/>
                                                @endif
                                                <a href="#!" data-mdb-toggle="modal" data-mdb-target="#exampleModal1">
                                                    <div class="mask"
                                                         style="background-color: rgba(251, 251, 251, 0.2);"></div>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-3 text-center">
                                    <button type="submit"
                                            class="btn btn-lg btn-success col-3">
                                        Удалить
                                    </button>
                                </div>
                                <div class="mt-3 text-center">
                                    <button type="button" class="btn btn-lg btn-success col-3" id="remove-album">
                                        Удалить альбом
                                    </button>
                                </div>
                            </form>
                        @endif
                    </section>

                    @if(!isset($editable))
                        <div class="col-lg-12 p-t-20">
                            <form method="post" action="{{route('upload')}}" id="dropzone" class="dropzone"
                                  enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" id="album" name="album" value="{{$album}}">
                                <div class="dz-message">
                                    <div class="dropIcon">
                                        <i class="material-icons">cloud_upload</i>
                                    </div>
                                    <h3>Кликните сюда чтобы загрузить файлы</h3>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
