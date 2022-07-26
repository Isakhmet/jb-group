<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="{{ URL::to('/') }}/assets/css/bootstrap/bootstrap.css" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::to('/') }}/assets/css/style.css" rel="stylesheet" type="text/css"/>

    <title>Hello, world!</title>
</head>
<body>
    <div class="container-login">
        <div class="text-center mt-5">
            <form action="{{ route('login') }}" class="login-form">
                <h1 class="h3 mb-3 font-weight-normal">Вход в систему</h1>
                <input class="form-control" list="datalistOptions" id="users" placeholder="Роль" required>
                <datalist id="datalistOptions">
                    <option value="San Francisco">
                    <option value="New York">
                    <option value="Seattle">
                    <option value="Los Angeles">
                    <option value="Chicago">
                </datalist>
                <input type="password" class="form-control" id="password" placeholder="Пароль" autocomplete>

                <div class="mt-3">
                    <button class="btn btn-lg btn-primary col-12">Войти</button>
                </div>
            </form>
        </div>
    </div>
<script src="{{ URL::to('/') }}/assets/js/bootstrap/bootstrap.js"></script>

</body>
</html>
