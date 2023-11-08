@extends('Sidebar/sidebar')
<link href="{{ asset('css/colaboradores/create.css') }}" rel="stylesheet">
@section('content')
    <h1>Criar Colaborador</h1>

    <form action="{{ route('colaboradores.store') }}" method="POST">
        @csrf
        <div>
            <label for="ramal">Ramal:</label>
            <input type="number" id="ramal" name="ramal" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" class="form-control" required>
        </div>


        <button type="submit" class="btn btn-primary">Salvar</button>
    </form>
@endsection
