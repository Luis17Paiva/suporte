@extends('sidebar/sidebar')
<link href="{{ asset('css/ramais/create.css') }}" rel="stylesheet">

@section('content')
    <title>Adicionar ramal</title>
    <div class="box">
        <h1>Adicionar ramal</h1>

        <form action="{{ route('ramal.update',$ramal->id) }}" method="POST">
            @csrf
            <div>
                <div class="input-field">
                    <i class='bx bx-id-card'></i>
                    <input type="number" id="ramal" name="ramal" value="{{$ramal->id}}" $class="form-control" required placeholder="Ramal">
                </div>
                <div class="input-field">
                    <i class='bx bx-user'></i>
                    <input type="text" id="nome" name="nome" value="{{$ramal->nome}}" class="form-control" required
                        placeholder="Nome">
                </div>
                <div class="input-checkbox">
                    <p class="text1">Excluido
                        <i class='bx bxs-user-x'></i>
                    <input type="checkbox" id="excluido" name="exluido" value="{{$ramal->excluido}}" class="form-control">
                    </p>
                </div>
                <button type="submit" class="btn btn-primary">
                    <span class="text1">Salvar</span>
                </button>
            </div>
        </form>
    </div>
@endsection
