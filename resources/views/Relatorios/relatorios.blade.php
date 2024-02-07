@extends('sidebar/sidebar')

@section('content')
    <link href="{{ asset('css/relatorios/relatorios.css') }}" rel="stylesheet">
    <script src="{{ asset('js/relatorios/relatorios.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <div class="box">
        <h1 class="titulo">Relatórios por Período</h1>

        <form action="{{ route('relatorios') }}" method="post" class='data'>
            @csrf
            <div class="data-input">
                <label for="data_inicio" class="text1 nav-text">Data Início:</label>
                <input id="data_inicio" class="dt" type="date" name="data_inicio"
                    value="{{ $dataInicio ? $dataInicio->format('Y-m-d') : '' }}" required>
            </div>
            <div class="data-input">
                <label for="data_fim" class="text1 nav-text1">Data Fim:</label>
                <input id="data_fim" class="dt" type="date" name="data_fim"
                    value="{{ $dataFim ? $dataFim->format('Y-m-d') : '' }}" required>
            </div>
            <button type="submit" class="gerar">
                <span class="text1 nav-text">Gerar Relatórios</span>
            </button>
        </form>

        <h2 class="titulo">Resultados</h2>

        @if (isset($relatorios) && count($relatorios) > 0)
            <div class="result">
                <ul class="lista">
                    <li class="text2"><strong class="text2">Tempo Médio de Espera:</strong>
                        {{ $relatorios['tempo_medio_espera'] }}</li>
                    <li class="text2"><strong class="text2">Tempo Médio de Atendimento:</strong>
                        {{ $relatorios['tempo_medio_atendimento'] }}</li>
                    <li class="text2"><strong class="text2">Tempo Médio de Desistência:</strong>
                        {{ $relatorios['tempo_medio_desistencia'] }}</li>
                    <li class="text2"><strong class="text2">Quantidade de Ligações:</strong>
                        {{ $relatorios['quantidade_ligacoes'] }}</li>
                    <li class="text2"><strong class="text2">Quantidade de Ligações Perdidas:</strong>
                        {{ $relatorios['quantidade_ligacoes_perdidas'] }}
                    </li class="text2">
                    <li class="text2"><strong class="text2">Quantidade de Ligações Atendidas:</strong>
                        {{ $relatorios['quantidade_ligacoes_atendidas'] }}</li>
                    <li class="text2"><strong class="text2">Média de Atendimentos:</strong>
                        {{ $relatorios['media_atendimentos'] }}</li>
                </ul>

                <div class="tabela">
                    <table id='table_colaborador'>
                        <thead>
                            <tr>
                                <th>Colaborador</th>
                                <th>Quantidade de Ligações Atendidas</th>
                                <th>Tempo Total de Atendimento</th>
                                <th>Tempo Médio de Atendimento</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($relatorios['dados_por_colaborador'] as $dados)
                                <tr>
                                    <td>{{ $dados['colaborador'] }}</td>
                                    <td>{{ $dados['quantidade_ligacoes_atendidas'] }}</td>
                                    <td>{{ $dados['tempo_total_atendimento'] }}</td>
                                    <td>
                                        @if ($dados['quantidade_ligacoes_atendidas'] > 0)
                                            {{ $dados['tempo_medio_atendimento'] }}
                                        @else
                                            00:00:00
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="grafico" id="graficoPizza"></div>
            </div>
        @else
            <p>Nenhum relatório disponível.</p>
        @endif

    </div>

@endsection
