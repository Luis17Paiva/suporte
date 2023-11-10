@extends('Sidebar/sidebar')
<link href="{{ asset('css/Acessos/acessos.css') }}" rel="stylesheet">

@section('content')
    @if (session('mensagem'))
        <div class="alert alert-success">
            {{ session('mensagem') }}
        </div>
    @endif
    <title>Acessos de Servidores</title>
    <div class="box">
        <h1 class="titulo">Acessos de Servidores</h1>
        <div class="button">
            @if (Route::has('acessos.create'))
                <a href="{{ route('acessos.create') }}">
                    <i class='bx bx-add-to-queue icon'></i>
                    <span class="text1">Adicionar Acesso</span>
                </a>
            @endif
        </div>

        <div class = "lista">
            <ul>
                @foreach ($acessos as $acesso)
                    <li>
                        <div>
                            <div class="text1">Empresa: {{ $acesso->empresa }}</div>
                            <div class="text1">Tipo do Acesso: {{ $acesso->tipo_acesso }}</div>
                        </div>
                        <div>
                            <div class="text1">ID: {{ $acesso->acesso_id }}</div>
                            <div class="text1">Senha: {{ $acesso->senha }}</div>
                        </div>
                        @if (Route::has('acessos.edit'))
                            <div class="button">
                                <a href="{{ route('acessos.edit', $acesso->id) }}">
                                    <i class='bx bxs-edit icon'></i>
                                    <span class="text1">Editar</span>
                                </a>
                            </div>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    </div>
@endsection
