<?php

namespace App\Http\Controllers;

use App\Models\Atendimento;
use Illuminate\Support\Facades\DB;
use DateTime;
use Illuminate\Http\Request;

class CentralController
{

    public function index()
    {
        return view("Central/central");
    }

    public function result()
    {
        // Define o fuso horário
        date_default_timezone_set('America/Sao_Paulo');

        // Obtém a data atual
        $data_atual = date('Y-m-d');

        // Consulta a quantidade de atendimentos na fila de espera
        $fila_qtd = DB::table('atendimento')
            ->where('status', '=', 'EM ESPERA')
            ->where('data_inclusao', '=', $data_atual)
            ->count();

        // Consulta a quantidade de atendimentos em andamento
        $atendendo_qtd = DB::table('atendimento')
            ->where('status', '=', 'EM ATENDIMENTO - AGUARDANDO DESLIGAMENTO')
            ->where('data_inclusao', '=', $data_atual)
            ->count();

        // Consulta a quantidade de atendimentos perdidos
        $perdidas_qtd = DB::table('atendimento')
            ->where('status', '=', 'PERDIDO')
            ->where('data_inclusao', '=', $data_atual)
            ->count();

        // Consulta a quantidade de atendimentos total
        $total = DB::table('atendimento')
            ->where('status', '<>', 'N/A URA')
            ->where('data_inclusao', '=', $data_atual)
            ->count();

        // Consulta os registros de atendimentos na fila de espera
        $fila_registros = DB::table('atendimento')
            ->where('status', '=', 'EM ESPERA')
            ->where('data_inclusao', '=', $data_atual)
            ->orderBy('hora_chamada')
            ->get();

        $fila_list = array();
        foreach ($fila_registros as $registro) {
            $hora_chamada = new DateTime($registro->hora_chamada);
            $hora_atual = new DateTime();

            $diferenca = $hora_atual->diff($hora_chamada);

            $tempo_de_espera = $diferenca->format('%H:%I:%S');

            $ura = $registro->ura;

            $fila_list[] = array(
                'hora_chamada' => $registro->hora_chamada,
                'tempo_de_espera' => $tempo_de_espera,
                'ura' => $ura
            );
        }

        // Consulta os registros de atendimentos em andamento
        $atend_registros = DB::table('atendimento')
            ->join('colaborador', 'atendimento.id_ramal', '=', 'colaborador.id')
            ->where('atendimento.status', '=', 'EM ATENDIMENTO - AGUARDANDO DESLIGAMENTO')
            ->where('atendimento.data_inclusao', '=', $data_atual)
            ->orderBy('atendimento.hora_atendimento')
            ->get();

        $atend_list = array();
        foreach ($atend_registros as $registro) {
            $hora_atendimento = new DateTime($registro->hora_atendimento);
            $hora_atual = new DateTime();

            $diferenca = $hora_atual->diff($hora_atendimento);

            $tempo_de_atendimento = $diferenca->format('%H:%I:%S');

            $atend_list[] = array(
                'colaborador' => $registro->nome,
                'tempo_de_atendimento' => $tempo_de_atendimento
            );
        }

        // Consulta os registros de atendimentos perdidos
        $perd_registros = DB::table('atendimento')
            ->where('status', '=', 'PERDIDO')
            ->where('data_inclusao', '=', $data_atual)
            ->where('ura', '<>', 'ADM')
            ->orderBy('hora_chamada', 'desc')
            ->get();

        $perd_list = array();
        foreach ($perd_registros as $registro) {
            $hora_chamada = new DateTime($registro->hora_chamada);
            $hora_desliga = new DateTime($registro->hora_desliga);

            $diferenca = $hora_desliga->diff($hora_chamada);

            $tempo_de_espera = $diferenca->format('%H:%I:%S');

            $perd_list[] = array(
                'numero' => $registro->numero,
                'tempo_de_espera' => $tempo_de_espera
            );
        }

        // Calcula a media do tempo de espera
        // Considerando clientes atendidos, quanto tempo esperaram na média
        $media_tempo_espera_seconds = Atendimento::whereDate('data_inclusao', $data_atual)
            ->where('status', '=', 'FINALIZADO')
            ->whereNotNull('hora_chamada')
            ->whereNotNull('hora_atendimento')
            ->get()
            ->map(function ($atendimento) {
                $hora_atendimento = new DateTime($atendimento->hora_atendimento);
                $hora_chamada = new DateTime($atendimento->hora_chamada);
                // Calcula a diferença entre as horas da ligação e a hora do atendimento
                return $hora_atendimento->getTimestamp() - $hora_chamada->getTimestamp(); // Retorna apenas os segundos para a média
            })
            ->avg();
        // Converte os segundos para o formato HH:mm:ss
        $media_tempo_espera = gmdate('H:i:s', $media_tempo_espera_seconds);


        // Calcula a media do tempo de atendimento
        // Considerando os clientes que foram atendidos, quanto tempo durou o atendimento na média
        $media_tempo_atendimento_seconds = Atendimento::whereDate('data_inclusao', $data_atual)
            ->where('status', '=', 'FINALIZADO')
            ->whereNotNull('hora_atendimento')
            ->whereNotNull('hora_desliga')
            ->get()
            ->map(function ($atendimento) {
                $hora_atendimento = new DateTime($atendimento->hora_atendimento);
                $hora_desliga = new DateTime($atendimento->hora_desliga);
                // Calcula a diferença entre as horas de atendimento e desligamento
                return $hora_desliga->getTimestamp() - $hora_atendimento->getTimestamp(); // Retorna a diferença em segundos
            })
            ->avg();
        $media_tempo_atendimento = gmdate('H:i:s', $media_tempo_atendimento_seconds);


        // Calcula a media do tempo de desistencia
        // Considernado os cliente que não foram atendidos, quanto tempo ficaram em espera na média
        $media_tempo_desistencia_seconds = Atendimento::whereDate('data_inclusao', $data_atual)
            ->where('status', '=', 'PERDIDO')
            ->whereNotNull('hora_chamada')
            ->whereNotNull('hora_desliga')
            ->get()
            ->map(function ($atendimento) {
                $hora_chamada = new DateTime($atendimento->hora_chamada);
                $hora_desliga = new DateTime($atendimento->hora_desliga);

                return $hora_desliga->getTimestamp() - $hora_chamada->getTimestamp(); // Retorna apenas os segundos para a média
            })
            ->avg();
        $media_tempo_desistencia =  gmdate('H:i:s', $media_tempo_desistencia_seconds);

        // Calcula o maior tempo de espera considerando os que foram e não foram atendidos
        $maior_tempo_espera_seconds = Atendimento::whereDate('data_inclusao', $data_atual)
            ->where('status', '=', 'FINALIZADO')
            ->whereNotNull('hora_chamada')
            ->whereNotNull('hora_atendimento')
            ->get()
            ->map(function ($atendimento) {
                $hora_atendimento = new DateTime($atendimento->hora_atendimento);
                $hora_chamada = new DateTime($atendimento->hora_chamada);
                // Calcula a diferença entre as horas de atendimento e chamada ou desligamento em segundos
                return $hora_atendimento->getTimestamp() - $hora_chamada->getTimestamp(); // Retorna apenas os segundos
            })
            ->max();
        $maior_tempo_espera = gmdate('H:i:s',$maior_tempo_espera_seconds);

        $data = array(
            'f' => $fila_qtd,
            'a' => $atendendo_qtd,
            'p' => $perdidas_qtd,
            'fl' => $fila_list,
            'al' => $atend_list,
            'pl' => $perd_list,
            'me' => $media_tempo_espera,
            'ma' => $media_tempo_atendimento,
            'md' => $media_tempo_desistencia,
            'mt' => $maior_tempo_espera,
            't' => $total
        );

        // Converte o array em formato JSON 
        return response()->json($data);
    }

}

