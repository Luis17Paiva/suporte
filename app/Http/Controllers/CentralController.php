<?php

namespace App\Http\Controllers;

use App\Models\Atendimento;
use Illuminate\Support\Facades\DB;
use DateTime;

class CentralController
{


    public function index()
    {
        return view("central/central");
    }

    // Retorna a quantidade de ligações na data e status informado
    private function quantidade($data, $status = NULL)
    {

        if (is_null($status)) {
            return DB::table('atendimento')
                ->where('status', '<>', 'N/A URA')
                ->where('data_inclusao',$data)
                ->count();

        }
        return DB::table('atendimento')
            ->where('status',$status)
            ->where('data_inclusao',$data)
            ->count();
    }

    // Retorna a media de ligações na data por status
    private function Media($data, $status, $hora_inicial, $hora_final)
    {
        $tempo_seconds = Atendimento::whereDate('data_inclusao', $data)
            ->where('status',$status)
            ->whereNotNull($hora_inicial)
            ->whereNotNull($hora_final)
            ->get()
            ->map(function ($atendimento) use ($hora_inicial, $hora_final) {
                $hora_inicio = new DateTime($atendimento->{$hora_inicial});
                $hora_fim = new DateTime($atendimento->{$hora_final});
                return $hora_fim->getTimestamp() - $hora_inicio->getTimestamp();
            })
            ->avg();

        return gmdate('H:i:s', $tempo_seconds);
    }

    public function result()
    {
        // Denifição dos status de atendimento
        $emEspera = 'EM ESPERA';
        $emAtendimento = 'EM ATENDIMENTO - AGUARDANDO DESLIGAMENTO';
        $perdido = 'PERDIDO';
        $finalizado = 'FINALIZADO';
        // Define o fuso horário
        date_default_timezone_set('America/Sao_Paulo');

        // Obtém a data atual
        $data_atual = date('Y-m-d');

        // Consulta a quantidade de atendimentos por status
        $fila_qtd = $this->quantidade($data_atual, $emEspera);
        $atendendo_qtd = $this->quantidade($data_atual, $emAtendimento);
        $perdidas_qtd = $this->quantidade($data_atual, $perdido);
        $finalizado_qtd = $this->quantidade($data_atual, $finalizado);
        $total = $this->quantidade($data_atual);

        // Consulta os registros de atendimentos na fila de espera
        $fila_registros = DB::table('atendimento')
            ->where('status', $emEspera)
            ->where('data_inclusao',$data_atual)
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
            ->where('atendimento.status', '=', $emAtendimento)
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
            ->where('status',$perdido)
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

        // Calcula as medias se houver ao menos uma ligação finalizada
        $media_tempo_espera = '00:00:00';
        $media_tempo_atendimento = '00:00:00';
        $media_tempo_desistencia = '00:00:00';
        $maior_tempo_espera = '00:00:00';
        if($finalizado_qtd > 0){
            $media_tempo_espera = $this->Media($data_atual, $finalizado, 'hora_chamada', 'hora_atendimento');
            $media_tempo_atendimento = $this->Media($data_atual, $finalizado, 'hora_atendimento', 'hora_desliga');
            if($perdidas_qtd > 0){
                $media_tempo_desistencia = $this->Media($data_atual, $perdido, 'hora_chamada', 'hora_desliga');
            }

            // Calcula o maior tempo de espera considerando os que foram e não foram atendidos
            $maior_tempo_espera_seconds = Atendimento::whereDate('data_inclusao', $data_atual)
                ->where('status', $finalizado)
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
            $maior_tempo_espera = gmdate('H:i:s', $maior_tempo_espera_seconds);
        }

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

