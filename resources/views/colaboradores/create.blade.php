@extends('sidebar/sidebar')
<link href="{{ asset('css/colaboradores/create.css') }}" rel="stylesheet">

@section('content')
    <title>Adicionar colaborador</title>
    <div class="box">
        <h1>Adicionar colaborador</h1>

        <form action="{{ route('colaboradores.store') }}" method="POST">
            @csrf
            <div>
                <div class="input-field">
                    <i class='bx bx-id-card'></i>
                    <input type="number" id="ramal" name="ramal" class="form-control" required 
                    placeholder="Ramal">
                </div>
                <div class="input-field">
                    <i class='bx bx-user'></i>
                    <input type="text" id="nome" name="nome" class="form-control" required
                        placeholder="Nome">
                </div>
                <button type="submit" class="btn btn-primary">
                    <span class="text1">Salvar</span>
                </button>
            </div>
        </form>
    </div>
@endsection
