@extends('sidebar/sidebar')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/colaboradores/colaboradores.js') }}"></script>
<link href="{{ asset('css/colaboradores/colaboradores.css') }}" rel="stylesheet">


@section('content')
    <title>Colaboradores</title>
    <div class="box">
        <h1 class="titulo">Colaboradores</h1>

        <div class="button">
            <a href="{{ route('colaboradores.create') }}">
                <i class='bx bx-add-to-queue icon'></i>
                <span class="text1">Adicionar Colaborador</span>
            </a>
        </div>

        <div class = "lista">
            <ul>
                @foreach ($colaboradores as $colaborador)
                    <li>
                        <div>
                            <div class="text1">Ramal: {{ $colaborador->id }}</div>
                            <div class="text1">Colaborador: {{ $colaborador->nome }}</div>
                        </div>
                        <div>
                            <div class="text1">Status: {{ $colaborador->excluido ? 'Excluído' : 'Ativo' }}</div>
                        </div>

                        <div class="button">
                            <a href="{{ route('colaboradores.edit', $colaborador->id) }}">
                                <i class='bx bxs-edit icon'></i>
                                <span class="text1">Editar</span>
                            </a>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection
