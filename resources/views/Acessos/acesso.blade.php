@extends('Sidebar/sidebar')


<link href="{{ asset('css/Acessos/acessos.css') }}" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="{{ asset('js/Acessos/acesso.js') }}" defer></script>
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
                        <img src="{{ asset('assets/Acesso/supremo.png') }}" class="logo">
                        <div>
                            <div class="text1">Empresa: {{ $acesso->empresa }}</div>
                            <div class="text1">Tipo do Acesso: {{ $acesso->tipo_acesso }}</div>
                        </div>
                        <div class="button">
                            <a class="open-modal-confirm" data-target="#modal-confirm-{{ $acesso->id }}">Acessar</a>
                        </div>
                        <div class="button">
                            <a class="open-modal-hist" data-target="#modal-hist-{{ $acesso->id }}">Histórico</a>
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
                    <div class="modal hide modal-confirm" id="modal-confirm-{{ $acesso->id }}">
                        <div class="modal-header">
                            <h2>Aviso de Privacidade</h2>
                            <div class="button m">
                                <a class="close-modal-confirm">Voltar</a>
                            </div>
                        </div>
                        <div class="modal-body">
                            <p>Você declara estar ciente da Lei Geral de Proteção de Dados (LGPD), que regula o tratamento de dados pessoais
                                no Brasil.</p>
                            <p>Você declara que tem a competência para acessar os dados pessoais dos clientes da empresa.</p>
                            <p>Você compromete-se a utilizar os dados pessoais dos clientes apenas para os fins autorizados pela LGPD.</p>
                            <p>Você compromete-se a manter a confidencialidade dos dados pessoais dos clientes.</p>
                            <p>Você compromete-se a informar à empresa "{{ $acesso->empresa }}" caso ocorra qualquer incidente de segurança
                                que possa comprometer os dados pessoais dos clientes.</p>
                            <p>Lei nº 13.709, de 14 de agosto de 2018.</p>
                            <div class="button m">
                                <a class="open-modal-acesso" data-target="#modal-acesso-{{ $acesso->id }}">Acessar</a>
                            </div>
                        </div>
                    </div>
                    <div class="modal hide modal-acesso" id="modal-acesso-{{ $acesso->id }}">
                        <div class="modal-header">
                            <h2>Servidor da {{ $acesso->empresa }}</h2>
                            <div class="button m">
                                <a class="close-modal-acesso">Voltar</a>
                            </div>
                        </div>
                        <div class="modal-body">
                            <p>Tipo de acesso: {{ $acesso->tipo_acesso }}</p>
                            <p>ID: {{ $acesso->acesso_id }}</p>
                            <p>Senha: {{ $acesso->senha }}</p>
                        </div>
                    </div>
                    <div class="modal hide modal-hist" id="modal-hist-{{ $acesso->id }}">
                        <div class="modal-header">
                            <h2>Histórico de acesso da empresa: {{ $acesso->empresa }}</h2>
                            <div class="button m">
                                <a class="close-modal-hist">Voltar</a>
                            </div>
                        </div>
                        <div class="modal-body">
                            <form id="form-filtrar-hist" data-acesso-id="{{ $acesso->id }}">
                                @csrf
                                <label for="data_inicial">Data Inicial:</label>
                                <input type="date" name="data_inicial" required>

                                <label for="data_final">Data Final:</label>
                                <input type="date" name="data_final" required>

                                <button type="submit">Gerar</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </ul>
        </div>
    </div>
    <!-- Modal -->
    <div id="fade" class="hide"></div>
    
@endsection
