@extends('Sidebar/sidebar')

@section('content')
    <h1>Colaborador Criado</h1>

    <p>O colaborador foi criado com sucesso.</p>

    <a href="{{ route('colaboradores') }}" class="btn btn-primary">Voltar para a lista de colaboradores</a>
@endsection
