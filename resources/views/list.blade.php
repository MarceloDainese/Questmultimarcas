@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div>
        <div class="row">
            <div class="col">
                <h3>Quest Multi Marcas - Lista de carros do Banco de Dados</h3>
                <hr>
            </div>
        </div>
    </div>
    <hr>
    <div>
        @if($carsBd)
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Modelo</th><th>Ano</th><th>Combustível</th><th>Portas</th><th>Quilometragem</th><th>Câmbio</th><th>Cor</th><th>Preço</th><th></th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($carsBd as $car )
                    <tr>
                        <td><a href="{{ $car->link }}" class="nav-link" target="_blank">{{ $car->nome_veiculo }}</a></td><td>{{ $car->ano }}</td><td>{{ $car->combustivel }}</td><td>{{ $car->portas }}</td><td>{{ $car->quilometragem }}</td><td>{{ $car->cambio }}</td><td>{{ $car->cor }}</td><td>{{ $car->preco }}</td><td>
                            <form action="{{ route('deleteCar', $car->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Excluir</button></form></td>
                    </tr>
                @endforeach
                </tbody>
                </table>
        @endif
    </div>
</div>
@endsection
