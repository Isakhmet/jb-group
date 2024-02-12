@extends('layouts.main')
@section('script')
    <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap-switch-button@1.1.0/css/bootstrap-switch-button.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap-switch-button@1.1.0/dist/bootstrap-switch-button.min.js"></script>
    <script>
        $(document).ready(function () {
            $('input:radio[name=type]').change(function () {
                if ($("input[name='type']:checked").val() == 'video') {
                    $('#images').css('display', 'none');
                    $('#video').css('display', 'flex');
                }else {
                    $('#images').css('display', 'flex');
                    $('#video').css('display', 'none');
                }
            })
        });
    </script>
@endsection

@section('styles')
    <style>
        .radio {
            margin-top: 10px;
            font-size: 20px;
            font-weight: 500;
            text-transform: capitalize;
            display: inline-block;
            vertical-align: middle;
            position: relative;
            padding-left: 30px;
            cursor: pointer;
        }

        .radio + .radio {
            margin-left: 20px;
        }

        .radio input[type="radio"] {
            display: none;
        }

        .radio span {
            height: 20px;
            width: 20px;
            border-radius: 50%;
            border: 3px solid #47474c;
            display: block;
            position: absolute;
            left: 0;
            top: 7px;
        }

        .radio span:after {
            content: "";
            height: 8px;
            width: 8px;
            background: #47474c;
            display: block;
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%) scale(0);
            border-radius: 50%;
            transition: 300ms ease-in-out 0s;
        }

        .radio input[type="radio"]:checked ~ span:after {
            transform: translate(-50%, -50%) scale(1);
        }
    </style>
@endsection
@section('content')
    <div class="card">
        <div class="card-header">{{ __('titles.media') }}</div>
        <div class="row ml-2">
            <div class="radio-group">
                <label class="radio">
                    <input type="radio" value="image" name="type">
                    Image
                    <span></span>
                </label>
                <label class="radio">
                    <input type="radio" value="video" name="type">
                    Video
                    <span></span>
                </label>
            </div>
        </div>
        <div class="card-body p-2 m">
            <div class="text-center mt-5">
                @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        <p>{{ $message }}</p>
                    </div>
                @endif
            </div>
            @include('layouts.gallery')
            @include('layouts.video')
        </div>
    </div>
@endsection
