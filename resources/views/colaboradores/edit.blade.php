<!-- resources/views/colaboradores/editar.blade.php -->
@extends('Sidebar/sidebar')
<link href="{{ asset('css/colaboradores/edit.css') }}" rel="stylesheet">
@section('content')
    <h1>Editar Colaborador</h1>

    <form method="POST" action="{{ route('colaboradores.update', $colaborador->id) }}">

        @csrf
        @method('PUT')
        <div>
            <label for="ramal">Ramal:</label>
            <input type="number" id="ramal" name="ramal" value="{{ $colaborador->id}}" required>
        </div>
        <div>
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" value="{{ $colaborador->nome }}" required>
        </div>

        <div>
            <label for="excluido">Excluído:</label>
            <input type="checkbox" id="excluido" name="excluido" {{ $colaborador->excluido ? 'checked' : '' }}>
        </div>

        <button type="submit">Salvar</button>
    </form>
@endsection
