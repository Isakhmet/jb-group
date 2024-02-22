@extends('layouts.main')
@section('styles')
    <link href="{{asset('fonts/material-design-icons/material-icon.css')}}" rel="stylesheet" type="text/css"/>
    <style>
        .icon-holder {
            display: flex;
            flex-direction: row;
            cursor: pointer;
        }

        .icon-label, .icon-link {
            font-size: 1.25rem;
            margin-left: 8px;
            color: black;
            text-decoration: none;
        }

        .icon-link:hover {
            color: black;
            text-decoration: none;
        }

        .material-icons {
            font-size: 27px;
        }

        .mt-10 {
            margin-top: 4rem;
        }

        .nav-item:before {
            content: "•";
            color: inherit;
            padding-left: 0.6rem;
            padding-right: 0.6rem;
            opacity: 0.8;
        }
    </style>
@endsection
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                @can('viewAny', \App\Models\Currency::class)
                    <div class="card">
                        <div class="card-header">{{ __('titles.news') }}</div>
                        <div class="card-body">
                            <div class="text-center mt-5">
                                @if ($message = Session::get('success'))
                                    <div class="alert alert-success">
                                        <p>{{ $message }}</p>
                                    </div>
                                @endif
                            </div>
                            @foreach($events as $event)
                                <div class="card mt-10">
                                    <div class="card card-header"
                                         @if($event->type === 'Срочное')
                                         style="background-color: red; color: white"
                                        @endif>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="nav nav-divider">
                                                <h6 class="card-title mb-0"> {{$event->title}} </h6>
                                                <span class="nav-item small"> {{$event->created_at}}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <textarea class="form-control mb-3" name="message" rows="5"
                                                  readonly>{{$event->message}}</textarea>
                                        @if(isset($event->image))
                                            <img class="card-img"
                                                 src="{{asset('storage/news/images/'.$event->image)}}"
                                                 alt="Post">
                                        @endif
                                        @if($event->file)
                                            <div class="nav nav-stack py-3 small">
                                                <div class="icon-holder">
                                                    <i class="material-icons f-left">file_download</i>
                                                    <p class="icon-label">
                                                        <a class="icon-link"
                                                           href="{{route('upload-files', ['path' => '/news/files/'.$event->file])}}">{{substr($event->file, strpos($event->file, ".")+1)}}</a>
                                                    </p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endcan
            </div>
        </div>
    </div>
@endsection
