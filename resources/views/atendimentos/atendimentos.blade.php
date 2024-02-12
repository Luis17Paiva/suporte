<!DOCTYPE html>
@extends('sidebar/sidebar')
@section('content')
<html>
<head>
    <title>Central</title>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700&display=swap" rel="stylesheet" />
    <link href="{{ asset('css/atendimentos/atendimentos.css') }}" rel="stylesheet" />
    <link rel="shortcut icon" href="#" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="{{ asset('js/atendimentos/script.js') }}" defer></script>
</head>
<body>
    <section class="central">
        <div class="total">
            <p class="qtd">Total de Ligações<br />
                <span class = "qtd" id="total_qtd"></span>
            </p>
        </div>
        <div class="box">
            <div class="status">
                <h1>Em Espera</h1>
                <p class="qtd" id="fila_qtd"></p>
                <p class="text">Maior Tempo De Espera<br /><span class="media" id="maior_espera"></span></p>
                <p class="text">Tempo de Espera Médio <br /><span class="media" id="tempo-espera-medio"></span></p>
                <table>
                    <thead>
                        <tr>
                            <th>Hora chamada</th>
                            <th>Tempo de Espera</th>
                        </tr>
                    </thead>
                    <tbody class = "tabela" id="tabela-espera"></tbody>
                </table>
            </div>
            <div class="status">
                <h1>Em Atendimento</h1>
                <p class="qtd" id="atendendo_qtd"></p>
                <p class="text">Tempo de Atendimento Médio<br /><span class="media" id="tempo-atendimento-medio"></span></p>
                <table>
                    <thead>
                        <tr>
                            <th>Colaborador</th>
                            <th>Tempo Atendendo</th>
                        </tr>
                    </thead>
                    <tbody class = "tabela" id="tabela-atendendo" ></tbody>
                </table>
            </div>
            <div class="status">
                <h1>Perdidas</h1>
                <p class="qtd" id="perdidas_qtd"></p>
                <p class="text">Tempo de Desistência Médio <br /><span span class="media" id="tempo-desistencia-medio"></span></p>
                <table>
                    <thead>
                        <tr>
                            <th>Número</th>
                            <th>Tempo de Espera</th>
                        </tr>
                    </thead>
                    <tbody class ="tabela" id="tabela-perdidas"></tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
</body>
</html>
