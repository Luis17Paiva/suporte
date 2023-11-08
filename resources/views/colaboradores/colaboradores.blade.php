@extends('Sidebar/sidebar')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/colaboradores/colaboradores.js') }}"></script>
<link href="{{ asset('css/Colaboradores/colaboradores.css') }}" rel="stylesheet">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>



@section('content')
<title>Colaboradores</title>
<div class="box">
    <h1 class="titulo">Colaboradores</h1>
    
    <div class="button">
        <a href="{{ route('colaboradores.create') }}" >
            <i class='bx bx-add-to-queue icon'></i>
         <span class="text">Adicionar Colaborador</span>
        </a>
    </div>


    <div class = "tabela">
        <table>
            <thead>
                <tr>
                    <th>Ramal</th>
                    <th>Nome</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($colaboradores as $colaborador)
                    <tr>
                        <td>{{ $colaborador->id }}</td>
                        <td>{{ $colaborador->nome }}</td>
                        <td>{{ $colaborador->excluido ? 'Excluído' : 'Ativo' }}</td>
                        <td>
                            <div class="button">
                                <a href="{{ route('colaboradores.edit', $colaborador->id) }}" >
                                    <i class='bx bxs-edit icon'></i>
                                 <span class="text">Editar</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
