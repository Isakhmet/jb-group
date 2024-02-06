@extends('layouts.main')

@section('styles')
    <link href="{{asset('fonts/material-design-icons/material-icon.css')}}" rel="stylesheet" type="text/css"/>

    <style>
        .icon-holder {
            display: flex;
            flex-direction: row;
            cursor: pointer;
        }

        .icon-label {
            font-size: 1.25rem;
            margin-left: 8px;
        }

        .material-icons {
            font-size: 27px;
        }

    </style>
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                @can('create', \App\Models\Currency::class)
                    <div class="card">
                        <div class="card-header">{{ __('Новости') }}</div>

                        <div class="card-body p-2">
                            <div class="text-center mt-5">
                                <form method="post" action="{{ url('/events/'.$event->id) }}" class="login-form"
                                      enctype="multipart/form-data">
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
                                    @method('PUT')
                                    <input type="text" class="form-control mb-3" id="title" name="title"
                                           value="{{$event->title}}"
                                           required>
                                    <textarea class="form-control mb-3" name="message" rows="5"
                                              required>{{$event->message}}</textarea>

                                    <label for="image" class="col-form-label">Фото</label>
                                    <input type="text" hidden name="old_image" class="form-control" id="image"
                                           value="{{$image}}" readonly>

                                    <input type="file" name="image" class="form-control" id="image"
                                           onchange="previewImage(this)">
                                    <div class="form-control" id="imageBlock"
                                         @if(!isset($image)) style="display: none;" @endif>
                                        @if(isset($image))
                                            <img id="imagePreview" src="{{asset('storage/news/images/'.$event->image)}}"
                                                 alt="Image Preview"
                                                 style="max-width: 500px; max-height: 300px;">
                                            <button id="removeButton" class="btn btn-sm btn-danger col-4" type="button"
                                                    onclick="removeImage()">Удалить картинку
                                            </button>
                                        @endif
                                    </div>

                                    <label for="image" class="col-form-label">Файл</label>
                                    <div>
                                        @if(isset($file))
                                            <input type="text" name="old_file" class="form-control" id="file_name"
                                                   value="{{$file}}" readonly>
                                            <input type="file" style="display: none;" name="file_name"
                                                   class="form-control" id="file"
                                                   onclick="addFile()">
                                        @else
                                            <input type="file" name="file_name" class="form-control" id="file"
                                                   onclick="addFile()">
                                        @endif
                                        <button id="removeFile" class="btn btn-sm btn-danger col-4" type="button"
                                                @if(!isset($file)) style="display: none;" @endif
                                                onclick="removeFiles()">Удалить файл
                                        </button>
                                    </div>
                                    <select name="type" class="form-control" id="type"
                                            style="margin-top: 15px;">
                                        @foreach($types as $type)
                                            <option value="{{$type}}"
                                                    @if($type === $event->type) selected @endif>{{$type}}</option>
                                        @endforeach
                                    </select>
                                    <input class="form" id="is_fixed" type="checkbox" name="is_fixed"
                                           @if($event->is_fixed) checked @endif>
                                    <label for="is_fixed" class="col-form-label">Закрепить новость</label>
                                    <div class="mt-3">
                                        <button type="submit" class="btn btn-lg btn-success col-12">Обновить</button>
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

@section('script')
    <script>
        function previewImage(input) {
            let file = input.files[0];

            if (file) {
                let reader = new FileReader();

                reader.onload = function (e) {
                    document.getElementById('imagePreview').src = e.target.result;
                    document.getElementById('imageBlock').style.display = 'block';
                };

                reader.readAsDataURL(file);
            }
        }

        function removeImage() {
            document.getElementById('image').value = ''; // Clear the file input
            document.getElementById('imagePreview').src = '#'; // Clear the image preview source
            document.getElementById('imageBlock').style.display = 'none'; // Hide the image preview
        }

        function removeFiles() {
            document.getElementById('file').value = '';
            document.getElementById('file').style.display = 'block';
            document.getElementById('file_name').style.display = 'none';
            document.getElementById('file_name').value = '';
            document.getElementById('removeFile').style.display = 'none';
        }

        function addFile() {
            document.getElementById('removeFile').style.display = 'block';
        }
    </script>
@endsection
