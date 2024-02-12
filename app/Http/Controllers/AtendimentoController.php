<?php

namespace App\Http\Controllers;

use App\Models\Atendimento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use DateTime;

class AtendimentoController extends Controller
{
    protected $atendimento;
    protected $request;

    // Define o status
    public $emEspera = 'EM ESPERA';
    public $emAtendimento = 'EM ATENDIMENTO - AGUARDANDO DESLIGAMENTO';
    public $perdido = 'PERDIDO';
    public $finalizado = 'FINALIZADO';
    public $desistiu = 'N/A URA';

    // Cria uma string com todos os status
    public $statusOptions;
    public function __construct(Atendimento $atendimento, Request $request)
    {
        $this->atendimento = $atendimento;
        $this->request = $request;
        $this->statusOptions = implode(',', [$this->emAtendimento, $this->finalizado, $this->desistiu, $this->perdido]);
    }

    public function index()
    {
        return view("atendimentos/atendimentos");
    }
    // Registra um novo atendimento
    public function storeAtendimento($data)
    {

        $validator = Validator::make($data->all(), [
            'numero' => ['required', 'string', 'max:15'],
            'ura' => ['required', 'string', 'max:5'],
            'id_asterisk' => ['required', 'string', 'max:25'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $atendimento = new Atendimento;
        $atendimento->numero = $data->input('numero');
        $atendimento->ura = $data->input('ura');
        $atendimento->hora_chamada = date('H:i:s');
        $atendimento->id_asterisk = $data->input('id_asterisk');
        $atendimento->status = $this->emEspera;
        $atendimento->save();

        return response()->json(['Atendimento criado com sucesso.'],200);
    }
    // Altera o status do atendimento
    public function updateStatus($id_asterisk, $status = 'N/A URA')
    {

        // Valida os dados
        $rules = [
            'id_asterisk' => ['required,string,max:25'],
            'status' => ['required,string,in:' . $this->statusOptions],
        ];
        $messages = [
            'status.in' => 'O status fornecido é inválido.',
        ];
        $validator = Validator::make(compact('id_asterisk', 'status'), $rules, $messages);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // Altera status e hora 
        $atendimento = Atendimento::where('id_asterisk', $id_asterisk)->firstOrFail();
        switch ($status) {
            case $this->emAtendimento:
                $atendimento->status = $status;
                $atendimento->hora_atendimento = date('H:i:s');
                $atendimento->save();
                return response()->json(['Status alterado para: ' . $status],200);
            case $this->finalizado:
                $atendimento->status = $status;
                $atendimento->hora_desliga = date('H:i:s');
                $atendimento->save();
                return response()->json(['Status alterado para: ' . $status],200);
            case $this->perdido:
                $atendimento->status = $this->perdido;
                $atendimento->hora_desliga = date('H:i:s');
                $atendimento->save();
                return response()->json(['Status alterado para: '. $status],200);
            default:
                $atendimento->status = $status;
                $atendimento->hora_desliga = date('H:i:s');
                $atendimento->save();
                return response()->json(['Status alterado para: '. $status],200);
        }
    }
    // Altera a URA
    public function updateUra($id_asterisk,$ura,$ramal){

        // Valida os daddos
        $validator = Validator::make(['id_asterisk' => $id_asterisk, 'ura' => $ura], [
            'id_asterisk' => ['required', 'string', 'max:25'],
            'ura' => ['required', 'string', 'max:5'],
            'ramal'=> ['required','int','max:10']
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // Altera status e hora 
        $atendimento = Atendimento::where('id_asterisk', $id_asterisk)->firstOrFail();
        $atendimento->$ura = $ura;
        $atendimento->$ramal = $ramal;
        return response()->json(['Ura Alterada para '. $ura],200);
    }


    //// CODIGO ABAIXO É TEMPORÁRIO
    // Retorna a quantidade de ligações na data e status informado
    private function quantidadeAtendimentos($data, $status = NULL)
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
    private function mediaAtendimentos($data, $status, $hora_inicial, $hora_final)
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
    // Retorna a maior diferença de tempo entre a hora final - hora inicial pelo status e data informada
    private function maxAtendimentos($data, $status, $hora_inicial, $hora_final)
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
            ->max();

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
        $fila_qtd = $this->quantidadeAtendimentos($data_atual, $emEspera);
        $atendendo_qtd = $this->quantidadeAtendimentos($data_atual, $emAtendimento);
        $perdidas_qtd = $this->quantidadeAtendimentos($data_atual, $perdido);
        $finalizado_qtd = $this->quantidadeAtendimentos($data_atual, $finalizado);
        $total = $this->quantidadeAtendimentos($data_atual);

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
            $media_tempo_espera = $this->mediaAtendimentos($data_atual, $finalizado, 'hora_chamada', 'hora_atendimento');
            $media_tempo_atendimento = $this->mediaAtendimentos($data_atual, $finalizado, 'hora_atendimento', 'hora_desliga');
            if($perdidas_qtd > 0){
                $media_tempo_desistencia = $this->mediaAtendimentos($data_atual, $perdido, 'hora_chamada', 'hora_desliga');
            }
            // Calcula o maior tempo de espera considerando os que foram e não foram atendidos
            $maior_tempo_espera = $this->maxAtendimentos($data_atual,$finalizado,'hora_chamada','hora_atendimento');
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
