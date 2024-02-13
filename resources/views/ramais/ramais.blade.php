@extends('sidebar/sidebar')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/ramais/ramais.js') }}"></script>
<link href="{{ asset('css/ramais/ramais.css') }}" rel="stylesheet">


@section('content')
    <title>Ramais</title>
    <div class="box">
        <h1 class="titulo">Ramais</h1>

        <div class="button">
            <a href="{{ route('ramal.create') }}">
                <i class='bx bx-add-to-queue icon'></i>
                <span class="text1">Adicionar ramal</span>
            </a>
        </div>

        <div class = "lista">
            <ul>
                @foreach ($ramais as $ramal)
                    <li>
                        <div>
                            <div class="text1">Ramal: {{ $ramal->id }}</div>
                            <div class="text1">ramal: {{ $ramal->nome }}</div>
                        </div>
                        <div>
                            <div class="text1">Status: {{ $ramal->excluido ? 'Excluído' : 'Ativo' }}</div>
                        </div>

                        <div class="button">
                            <a href="{{ route('ramais.edit', $ramal->id) }}">
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
