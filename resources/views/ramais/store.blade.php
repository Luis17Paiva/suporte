@extends('sidebar/sidebar')

@section('content')
    <h1>Ramal Criado</h1>

    <p>O ramal foi criado com sucesso.</p>

    <a href="{{ route('ramais') }}" class="btn btn-primary">Voltar para a lista de ramais</a>
@endsection
