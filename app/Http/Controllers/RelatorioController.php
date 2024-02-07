<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Atendimento;
use App\Models\Colaborador;

class RelatorioController extends Controller
{
    protected $atendimento;
    protected $colaborador;
    protected $request;

    public function __construct(Atendimento $atendimento, Colaborador $colaborador, Request $request)
    {
        $this->atendimento = $atendimento;
        $this->colaborador = $colaborador;
        $this->request = $request;
    }

    public function index()
    {
        $dataAtual = now();
        $dataInicio = $this->request->input('data_inicio') ? Carbon::parse($this->request->input('data_inicio'))->startOfDay() : $dataAtual;
        $dataFim = $this->request->input('data_fim') ? Carbon::parse($this->request->input('data_fim'))->endOfDay() : $dataAtual;
        return view('relatorios.relatorios', compact('dataAtual', 'dataInicio', 'dataFim'));
    }
    public function showRelatorio()
    {
        $quantidadeLigacoes = 0; // Inicialize $quantidadeLigacoes antes de usá-lo

        $dataAtual = now()->format('Y-m-d');
        $dataInicio = $this->request->input('data_inicio') ? Carbon::parse($this->request->input('data_inicio'))->startOfDay() : null;
        $dataFim = $this->request->input('data_fim') ? Carbon::parse($this->request->input('data_fim'))->endOfDay() : null;

        $atendimentos = Atendimento::whereBetween('data_inclusao', [$dataInicio, $dataFim])->get();

        if (!$atendimentos->isEmpty()) {
            foreach ($atendimentos as $atendimento) {
                // Verifica se o status não é "FORA DO HORÁRIO"
                if ($atendimento->status !== 'FORA DO HORÁRIO') {
                    $quantidadeLigacoes++;
                }
            }
            if ($this->calcularDiferencaDias($atendimentos) > 0) {
                $relatorios = [
                    'tempo_medio_espera' => $this->calcularTempoMedioEspera($atendimentos),
                    'tempo_medio_atendimento' => $this->calcularTempoMedioAtendimento($atendimentos),
                    'tempo_medio_desistencia' => $this->calcularTempoMedioDesistencia($atendimentos),
                    'quantidade_ligacoes' => $quantidadeLigacoes,
                    'quantidade_ligacoes_perdidas' => $this->calcularQuantidadeLigacoesPerdidas($atendimentos),
                    'quantidade_ligacoes_atendidas' => $this->calcularQuantidadeLigacoesAtendidas($atendimentos),
                    'media_atendimentos' => $this->calcularMediaAtendimentos($atendimentos),
                    'dados_por_colaborador' => $this->getDadosPorColaborador($atendimentos),
                ];

                // Verifica se a variável $dataInicio é diferente de null
                if ($dataInicio !== null) {
                    return view('relatorios.relatorios', compact('relatorios', 'dataAtual', 'dataInicio', 'dataFim'));
                } else {
                    return view('relatorios.relatorios', compact('relatorios', 'dataAtual', 'dataFim'));
                }
            }
        } else {
            return $this->index();
        }
    }



    private function calcularTempoMedioEspera($atendimentos)
    {
        $tempoTotalEspera = 0;
        $contador = 0;

        foreach ($atendimentos as $atendimento) {
            if ($atendimento->hora_chamada && $atendimento->hora_atendimento) {
                $tempoEspera = strtotime($atendimento->hora_atendimento) - strtotime($atendimento->hora_chamada);
                $tempoTotalEspera += $tempoEspera;
                $contador++;
            }
        }

        return $contador > 0 ? gmdate('H:i:s', $tempoTotalEspera / $contador) : '00:00:00';
    }

    private function calcularTempoMedioAtendimento($atendimentos)
    {
        $tempoTotalAtendimento = 0;
        $contador = 0;

        foreach ($atendimentos as $atendimento) {
            if ($atendimento->hora_chamada && $atendimento->hora_desliga) {
                $tempoTotalAtendimento += strtotime($atendimento->hora_desliga) - strtotime($atendimento->hora_chamada);
                $contador++;
            }
        }

        $tempoMedioAtendimento = $contador > 0 ? $tempoTotalAtendimento / $contador : 0;
        return $this->converterSegundosParaTempo($tempoMedioAtendimento);
    }

    private function calcularTempoMedioDesistencia($atendimentos)
    {
        $tempoTotalDesistencia = 0;
        $contador = 0;

        foreach ($atendimentos as $atendimento) {
            if ($atendimento->status === 'PERDIDO' && $atendimento->hora_chamada && $atendimento->hora_desliga) {
                $tempoDesistencia = strtotime($atendimento->hora_desliga) - strtotime($atendimento->hora_chamada);
                $tempoTotalDesistencia += $tempoDesistencia;
                $contador++;
            }
        }

        return $contador > 0 ? gmdate('H:i:s', $tempoTotalDesistencia / $contador) : '00:00:00';
    }

    private function calcularQuantidadeLigacoesPerdidas($atendimentos)
    {
        $quantidadePerdidas = 0;

        foreach ($atendimentos as $atendimento) {
            if ($atendimento->status === 'PERDIDO') {
                $quantidadePerdidas++;
            }
        }

        return $quantidadePerdidas;
    }

    private function calcularQuantidadeLigacoesAtendidas($atendimentos)
    {
        $quantidadeAtendidas = 0;

        foreach ($atendimentos as $atendimento) {
            if ($atendimento->status === 'FINALIZADO') {
                $quantidadeAtendidas++;
            }
        }

        return $quantidadeAtendidas;
    }

    private function calcularMediaAtendimentos($atendimentos)
    {
        $totalAtendimentos = count($atendimentos);
        $totalDias = $this->calcularDiferencaDias($atendimentos);

        return $totalDias > 0 ? round($totalAtendimentos / $totalDias, 2) : 0;
    }

    private function calcularDiferencaDias($atendimentos)
    {
        if (count($atendimentos) > 0) {
            $dataInicio = Carbon::parse($atendimentos[0]->data_inclusao)->startOfDay();
            $dataFim = Carbon::parse($atendimentos[count($atendimentos) - 1]->data_inclusao)->endOfDay();

            return $dataInicio->diffInDays($dataFim) + 1;
        } else {
            return 0;
        }
    }

    private function getDadosPorColaborador($atendimentos)
    {
        $dadosPorColaborador = [];

        foreach ($atendimentos as $atendimento) {
            $colaboradorId = $atendimento->id_ramal;

            // Verifica se o colaborador já possui dados no array
            if (!isset($dadosPorColaborador[$colaboradorId])) {
                $colaboradorNome = Colaborador::where('id', $colaboradorId)
                    ->whereNotNull('nome')
                    ->value('nome');

                // Verifica se o nome do colaborador não é nulo
                if ($colaboradorNome !== null) {
                    $dadosPorColaborador[$colaboradorId] = [
                        'colaborador' => $colaboradorNome,
                        'quantidade_ligacoes_atendidas' => 0,
                        'tempo_medio_atendimento' => '00:00:00',
                        'tempo_total_atendimento' => 0,
                    ];
                }
            }

            // Atualiza os dados do colaborador
            if ($atendimento->status === 'FINALIZADO' && $atendimento->hora_atendimento && $atendimento->hora_desliga && $atendimento->id_ramal) {
                if (isset($dadosPorColaborador[$colaboradorId])) {
                    $dadosPorColaborador[$colaboradorId]['quantidade_ligacoes_atendidas']++;
                    $tempoTotalAtendimento = strtotime($atendimento->hora_desliga) - strtotime($atendimento->hora_atendimento);
                    $dadosPorColaborador[$colaboradorId]['tempo_total_atendimento'] += $tempoTotalAtendimento;
                }
            }
        }

        // Ordena os dados por quantidade de ligações atendidas em ordem decrescente
        $dadosPorColaborador = collect($dadosPorColaborador)->sortByDesc('quantidade_ligacoes_atendidas')->toArray();

        // Calcula o tempo médio de atendimento para cada colaborador
        foreach ($dadosPorColaborador as &$dados) {
            $quantidadeLigacoes = $dados['quantidade_ligacoes_atendidas'];
            $tempoTotalAtendimento = $dados['tempo_total_atendimento'];
            $tempoMedioAtendimento = $quantidadeLigacoes > 0 ? $tempoTotalAtendimento / $quantidadeLigacoes : 0;
            $dados['tempo_medio_atendimento'] = $this->converterSegundosParaTempo($tempoMedioAtendimento);
            $dados['tempo_total_atendimento'] = $this->converterSegundosParaTempo($tempoTotalAtendimento);
        }

        return $dadosPorColaborador;
    }



    private function converterSegundosParaTempo($segundos)
    {
        $horas = floor($segundos / 3600);
        $minutos = floor(($segundos % 3600) / 60);
        $segundos = $segundos % 60;

        return sprintf('%02d:%02d:%02d', $horas, $minutos, $segundos);
    }



}
