
@extends('layouts.main')

@section('content')
<div class="container-fluid">
</div>
<div class="login" >
    <form method="POST" action="{{ route('auth') }}">
        @csrf
        <div>
          <p class="h2 text-secondary">Login</p>
        </div>
        @if($errors->any())
            <div v-if="error" class="alert alert-danger" role="alert">
                <ul>
                    @foreach ( $errors->all() as $error )
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="mb-3 text-primary">
            <label>Email</label>
            <input type="email" class="form-control" name="email" placeholder="Email" required>
        </div>
        <div class="mb-3 text-primary">
            <label>Senha</label>
            <input type="password" class="form-control" name="password" placeholder="Senha" required>
        </div>
        <div class="mb-3">
          <button class="btn btn-primary btn-block">Enviar</button>
        </div>

    </form>
</div>
</div>
@endsection
