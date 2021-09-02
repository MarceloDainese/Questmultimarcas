@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div>
        <div class="row">
            <div class="col">
                <h3>Quest Multi Marcas - Capturar</h3>
                <hr>
            </div>
        </div>
    </div>
    <div class="form-group">
        <form action="{{ route('search') }}" class="action" method="GET">
            @csrf
            <div class="form-group-col-search" >
                <div class="col align-text-bottom">
                    Pesquisar:
                </div>
                <div class="col-x4">
                    <input class="form-control" type="text" name="search" >
                </div>
                <div class="col">
                    <button class="btn btn-primary">Capturar</button>
                </div>
            </div>
        </form>
    </div>
    <hr>
    <div>
        @if($error != "false")
            <div class="alert alert-danger text-center">
                {{ $error }}
            </div>
        @endif
        @if($success != "false")
            <div class="alert alert-success text-center">
                {{ $success }}
            </div>
        @endif
        @if($cars)
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Modelo</th><th>Ano</th><th>Combustível</th><th>Portas</th><th>Quilometragem</th><th>Câmbio</th><th>Cor</th><th>Preço</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($cars as $car )
                    <tr>
                        <td><a href="{{ $car['link'] }}" class="nav-link" target="_blank">{{ $car['name'] }}</a></td><td>{{ $car['ano'] }}</td><td>{{ $car['combustivel'] }}</td><td>{{ $car['portas'] }}</td><td>{{ $car['quilometragem'] }}</td><td>{{ $car['cambio'] }}</td><td>{{ $car['cor'] }}</td><td>{{ $car['price'] }}</td>
                    </tr>
                @endforeach
                </tbody>
                </table>
        @endif
        @if(!$cars)
            <div class="alert alert-danger text-center">
                Nada foi encontrado.
            </div>
        @endif
    </div>
</div>
@endsection
