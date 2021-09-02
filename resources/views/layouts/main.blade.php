<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Quest Multi Marcas</title>
    <style>
      * {
        padding: 1px;
    }
    .nav{
        justify-content: flex-end;
    }
    .login {
        display: flex;
        justify-content: center;
        padding: 10px;
    }
    .form-group{
        max-width:400px;
        padding: 0px;
        margin: 0px;
        display: flex;
        flex-direction: column;
    }
    .form-group-col-search{
        display: flex;
        flex-direction: row;
        align-items: center;
    }

    </style>
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/bootstrap.min.css') }}">
    <script src="{{ asset('assets/jquery.min.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('assets/fontawesome/font-awesome.min.css') }}">
    {{-- libs --}}
</head>
<body>
    @include('layouts.navbar')
    @yield('content')
    {{-- libs --}}
    <script src="{{ asset('assets/bootstrap/bootstrap.bundle.min.js') }}"></script>


</body>
</html>
