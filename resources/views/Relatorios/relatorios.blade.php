@extends('Sidebar/sidebar')
<link href="{{ asset('css/Relatorios/relatorios2.css') }}" rel="stylesheet">
@section('content')
    <script src="{{ asset('js/Relatorios/relatorios.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <head>
        <title>Relatórios</title>
    </head>

    <body class="box">
        <h1 class="titulo">Relatórios por Período</h1>

        <form action="{{ route('relatorios') }}" method="post" class='data'>
            @csrf
            <span class="text1 nav-text">Data Início:
                <input class="dt" type="date" name="data_inicio"
                    value="{{ $dataInicio ? $dataInicio->format('Y-m-d') : '' }}" required>
            </span>
            <span class="text1 nav-text1">Data Fim:
                <input class="dt" type="date" name="data_fim" value="{{ $dataFim ? $dataFim->format('Y-m-d') : '' }}"
                    required>
            </span>
            <button type="submit" class="gerar">
                <span class="text1 nav-text">Gerar Relatórios</span>
            </button>
        </form>

        <h2 class="titulo">Resultados</h2>

        @if (isset($relatorios) && count($relatorios) > 0)
        <div class="result">
            <table class="tabela">
                <thead>
                    <tr>
                        <th>Tempo Médio de Espera</th>
                        <th>Tempo Médio de Atendimento</th>
                        <th>Tempo Médio de Desistência</th>
                        <th>Quantidade de Ligações</th>
                        <th>Quantidade de Ligações Perdidas</th>
                        <th>Quantidade de Ligações Atendidas</th>
                        <th>Média de Atendimentos</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $relatorios['tempo_medio_espera'] }}</td>
                        <td>{{ $relatorios['tempo_medio_atendimento'] }}</td>
                        <td>{{ $relatorios['tempo_medio_desistencia'] }}</td>
                        <td>{{ $relatorios['quantidade_ligacoes'] }}</td>
                        <td>{{ $relatorios['quantidade_ligacoes_perdidas'] }}</td>
                        <td>{{ $relatorios['quantidade_ligacoes_atendidas'] }}</td>
                        <td>{{ $relatorios['media_atendimentos'] }}</td>
                    </tr>
                </tbody>
            </table>

            <table id='table_colaborador' class="tabela">
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

            <div class="grafico" id="graficoPizza">
                <script>
                    // Chama a função para renderizar o gráfico de pizza com os dados de atendimentos por colaborador
                     // Os dados serão obtidos do objeto "dadosPorColaborador" fornecido pelo Controller
                    renderizarGraficoDePizza({
                        colaboradores: {!! json_encode(array_column($relatorios['dados_por_colaborador'], 'colaborador')) !!},
                        valores: {!! json_encode(array_column($relatorios['dados_por_colaborador'], 'quantidade_ligacoes_atendidas')) !!},
                        cores: ['#EA526F', '#2EBFA5', '#2364AA', '#FF7F11', '#BCB6FF', '#820B8A', '#137547'],
                    });
                </script>

            </div>
        @else
            <p>Nenhum relatório disponível.</p>
        @endif

    @endsection

</body>
