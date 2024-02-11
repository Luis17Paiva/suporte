@extends('sidebar/sidebar')
<link href="{{ asset('css/colaboradores/create.css') }}" rel="stylesheet">

@section('content')
    <title>Adicionar colaborador</title>
    <div class="box">
        <h1>Adicionar colaborador</h1>

        <form action="{{ route('colaboradores.update',$colaborador->id) }}" method="POST">
            @csrf
            <div>
                <div class="input-field">
                    <i class='bx bx-id-card'></i>
                    <input type="number" id="ramal" name="ramal" value="{{$colaborador->id}}" $class="form-control" required placeholder="Ramal">
                </div>
                <div class="input-field">
                    <i class='bx bx-user'></i>
                    <input type="text" id="nome" name="nome" value="{{$colaborador->nome}}" class="form-control" required
                        placeholder="Nome">
                </div>
                <div class="input-checkbox">
                    <p class="text1">Excluido
                        <i class='bx bxs-user-x'></i>
                    <input type="checkbox" id="excluido" name="exluido" value="{{$colaborador->excluido}}" class="form-control">
                    </p>
                </div>
                <button type="submit" class="btn btn-primary">
                    <span class="text1">Salvar</span>
                </button>
            </div>
        </form>
    </div>
@endsection
