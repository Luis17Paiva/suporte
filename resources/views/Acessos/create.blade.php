@extends('Sidebar/sidebar')
<link href="{{ asset('css/Acessos/create.css') }}" rel="stylesheet">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
@section('content')
    <title>Adicionar acesso</title>
    <div class="box">
        <h1>Adicionar acesso</h1>

        <form action="{{ route('acessos.store') }}" method="POST">
            @csrf
            <div>
                <div class="input-field">
                    <i class='bx bx-user'></i>
                    <input type="text1" id="empresa" name="empresa" class="form-control" required placeholder="Empresa">
                </div>
                <div class="input-field">
                    <i class='bx bx-desktop'></i>
                    <input type="text1" id="tipo_acesso" name="tipo_acesso" class="form-control" required
                        placeholder="Tipo do Acesso">
                </div>
                <div class="input-field">
                    <i class='bx bx-id-card'></i>
                    <input type="text1" id="acesso_id" name="acesso_id" class="form-control" required placeholder="ID">
                </div>
                <div class="input-field">
                    <i class='bx bxs-key'></i>
                    <input type="text1" id="senha" name="senha" class="form-control" required placeholder="Senha">
                </div>
                <button type="submit" class="btn btn-primary">
                    <span class="text1">Salvar</span>
                </button>
            </div>
        </form>
    </div>
@endsection
