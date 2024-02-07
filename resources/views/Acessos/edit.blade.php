<!-- resources/views/colaboradores/editar.blade.php -->
@extends('sidebar/sidebar')
<link href="{{ asset('css/acessos/edit.css') }}" rel="stylesheet">
@section('content')
    <form method="POST" action="{{ route('acessos.update', $acesso->id) }}">
        <div class="box">
            <h1>Editar Acesso</h1>
                @csrf
                <div>
                    <div class="input-field">
                        <i class='bx bx-user'></i>
                        <input type="text1" id="empresa" name="empresa" value="{{ $acesso->empresa}}" class="form-control" required placeholder="Empresa">
                    </div>
                    <div class="input-field">
                        <i class='bx bx-desktop'></i>
                        <input type="text1" id="tipo_acesso" name="tipo_acesso" value="{{ $acesso->tipo_acesso}}" class="form-control" required
                            placeholder="Tipo do Acesso">
                    </div>
                    <div class="input-field">
                        <i class='bx bx-id-card'></i>
                        <input type="text1" id="acesso_id" name="acesso_id" value="{{ $acesso->acesso_id }}" class="form-control" required placeholder="ID">
                    </div>
                    <div class="input-field">
                        <i class='bx bxs-key'></i>
                        <input type="text1" id="senha" name="senha" value="{{ $acesso->senha }}" class="form-control" required placeholder="Senha">
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <span class="text1">Salvar</span>
                    </button>
                </div>
            </form>
        </div>
@endsection
