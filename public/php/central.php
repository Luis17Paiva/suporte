<?php

$diretorio_atual = __DIR__;
$diretorio_pai = dirname(dirname($diretorio_atual));

require $diretorio_pai.'/vendor/autoload.php';

// Define o fuso horário
date_default_timezone_set('America/Sao_Paulo');

// Carrega as variáveis de ambiente do arquivo .env
$dotenv = Dotenv\Dotenv::createImmutable("$diretorio_pai");
$dotenv->load();

// Carrega as configurações de conexão com o servidor PostgreSQL
$pgHost = $_ENV['DB_HOST'];
$pgPort = $_ENV['DB_PORT'];
$pgDbname = $_ENV['DB_DATABASE'];
$pgUser = $_ENV['DB_USERNAME'];
$pgpassword = $_ENV['DB_PASSWORD'];


// Conecta ao banco de dados
$dsn = "pgsql:
    host=$pgHost;
    port=$pgPort;
    dbname=$pgDbname;
    user=$pgUser;
    password=$pgpassword";
    
$conn = new PDO($dsn);

// Obtém a data atual
$data_atual = date('Y-m-d');

// Função para executar uma consulta e retornar um único valor
function consultarValor($conn, $query)
{
    $stmt = $conn->query($query);
    return $stmt->fetchColumn();
}

// Função para executar uma consulta e retornar vários resultados
function consultarRegistros($conn, $query)
{
    $stmt = $conn->query($query);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Consulta a quantidade de atendimentos na fila de espera
$query = "SELECT COUNT(id) FROM atendimentos WHERE status = 'EM ESPERA' AND data_inclusao = '$data_atual'";
$fila_qtd = consultarValor($conn, $query);

// Consulta a quantidade de atendimentos em andamento
$query = "SELECT COUNT(id) FROM atendimentos WHERE status = 'EM ATENDIMENTO - AGUARDANDO DESLIGAMENTO' AND data_inclusao = '$data_atual'";
$atendendo_qtd = consultarValor($conn, $query);

// Consulta a quantidade de atendimentos perdidos
$query = "SELECT COUNT(id) FROM atendimentos WHERE status = 'PERDIDO' AND data_inclusao = '$data_atual'";
$perdidas_qtd = consultarValor($conn, $query);

// Consulta os registros de atendimentos na fila de espera
$query = "SELECT hora_chamada, ura FROM atendimentos WHERE status = 'EM ESPERA' AND data_inclusao = '$data_atual' ORDER BY hora_chamada";
$fila_registros = consultarRegistros($conn, $query);

$fila_list = array();
foreach ($fila_registros as $registro) {
    $hora_chamada = new DateTime($registro['hora_chamada']);
    $hora_atual = new DateTime();

    $diferenca = $hora_atual->diff($hora_chamada);

    $tempo_de_espera = $diferenca->format('%H:%I:%S');

    $ura = $registro['ura'];

    $fila_list[] = array(
        'hora_chamada' => $registro['hora_chamada'],
        'tempo_de_espera' => $tempo_de_espera,
        'ura' => $ura
    );
}

// Consulta os registros de atendimentos em andamento
$query = "SELECT a.hora_atendimento, c.nome
            FROM atendimentos AS a
            INNER JOIN colaboradores AS c ON a.id_ramal = c.id
            WHERE a.status = 'EM ATENDIMENTO - AGUARDANDO DESLIGAMENTO' AND a.data_inclusao = '$data_atual' ORDER BY a.hora_atendimento";
$atend_registros = consultarRegistros($conn, $query);

$atend_list = array();
foreach ($atend_registros as $registro) {
    $hora_atendimento = new DateTime($registro['hora_atendimento']);
    $hora_atual = new DateTime();

    $diferenca = $hora_atual->diff($hora_atendimento);

    $tempo_de_atendimento = $diferenca->format('%H:%I:%S');

    $atend_list[] = array(
        'colaborador' => $registro['nome'],
        'tempo_de_atendimento' => $tempo_de_atendimento
    );
}

// Consulta os registros de atendimentos perdidos
$query = "SELECT numero, hora_chamada, hora_desliga FROM atendimentos WHERE status = 'PERDIDO' AND data_inclusao = '$data_atual' AND NOT ura = 'ADM' ORDER BY hora_chamada DESC";
$perd_registros = consultarRegistros($conn, $query);

$perd_list = array();
foreach ($perd_registros as $registro) {
    $hora_chamada = new DateTime($registro['hora_chamada']);
    $hora_desliga = new DateTime($registro['hora_desliga']);

    $diferenca = $hora_desliga->diff($hora_chamada);

    $tempo_de_espera = $diferenca->format('%H:%I:%S');

    $perd_list[] = array(
        'numero' => $registro['numero'],
        'tempo_de_espera' => $tempo_de_espera
    );
}

// Consulta a quantidade total de atendimentos do dia
$query = "SELECT COUNT(id) AS total FROM atendimentos WHERE data_inclusao = '$data_atual' AND status <> 'N/A URA'";
$total = consultarValor($conn, $query);
$total = gmdate($total);

$maior_tempo = '00:00:00';
$total_perdidas = '0';
$max_tempo_desistencia = '00:00:00';
$media_tempo_desistencia =  '00:00:00';
$media_tempo_atendimento = '00:00:00';
$media_tempo_espera= '00:00:00';

// Consulta a quantidade total de atendimentos do dia
$query = "SELECT COUNT(id) AS total FROM atendimentos WHERE status = 'FINALIZADO' AND data_inclusao = '$data_atual'";
$total_at = consultarValor($conn, $query);
$total_at = gmdate($total);

if ($total_at > 0) {
    // Consulta para calcular a média de tempo de atendimento
    $query = "SELECT ROUND(AVG(EXTRACT(EPOCH FROM hora_desliga - hora_atendimento))) AS media FROM atendimentos WHERE (hora_atendimento IS NOT NULL AND hora_desliga IS NOT NULL) AND status = 'FINALIZADO' AND data_inclusao = '$data_atual'";
    $media_tempo_atendimento = consultarValor($conn, $query);
    if ($media_tempo_atendimento === NULL || $media_tempo_atendimento <= 0){
        $media_tempo_atendimento = '5';
        $media_tempo_atendimento = gmdate('H:i:s', strtotime($media_tempo_atendimento));
    }else {
        $media_tempo_atendimento = gmdate('H:i:s',$media_tempo_atendimento);
    }

    // Consulta para calcular a média de tempo de espera
    $query = "SELECT ROUND(AVG(EXTRACT(EPOCH FROM hora_atendimento - hora_chamada))) AS media FROM atendimentos WHERE  hora_chamada IS NOT NULL AND hora_atendimento IS NOT NULL AND data_inclusao = '$data_atual'";
    $media_tempo_espera = consultarValor($conn, $query);
    if ( $media_tempo_espera === NULL){
        $media_tempo_espera = '0';
        $media_tempo_espera = gmdate('H:i:s', strtotime($media_tempo_espera));
    }else {
        $media_tempo_espera = gmdate('H:i:s',$media_tempo_espera);
    }

    // Consulta a quantidade de atendimentos perdidos do dia
    $query = "SELECT COUNT(id) AS total_perdidas FROM atendimentos WHERE status = 'PERDIDO' AND data_inclusao = '$data_atual' AND NOT ura = 'ADM'";
    $total_perdidas = consultarValor($conn, $query);
    $total_perdidas = gmdate($total_perdidas);

    if ($total_perdidas > 0) {
        // Consulta para calcular o maior tempo de desistência
        $query = "SELECT MAX(EXTRACT(EPOCH FROM hora_desliga - hora_chamada)) AS max FROM atendimentos WHERE status = 'PERDIDO' AND data_inclusao = '$data_atual'";
        $max_tempo_desistencia = consultarValor($conn, $query);
        $max_tempo_desistencia = gmdate('H:i:s', $max_tempo_desistencia);

        // Consulta para calcular a média de tempo de desistência
        $query = "SELECT ROUND(AVG(EXTRACT(EPOCH FROM hora_desliga - hora_chamada))) AS media FROM atendimentos WHERE status = 'PERDIDO' AND data_inclusao = '$data_atual'";
        $media_tempo_desistencia = consultarValor($conn, $query);
        $media_tempo_desistencia = gmdate('H:i:s', $media_tempo_desistencia);
    }

    // Consulta para calcular o maior tempo de espera
    $query = "SELECT MAX(EXTRACT(EPOCH FROM hora_atendimento - hora_chamada)) AS max FROM atendimentos WHERE hora_chamada IS NOT NULL AND hora_atendimento IS NOT NULL AND data_inclusao = '$data_atual'";
    $maior_tempo_espera = consultarValor($conn, $query);
    if ($maior_tempo_espera == NULL){
        $maior_tempo_espera = '0';
        $maior_tempo_espera = gmdate('H:i:s', strtotime($maior_tempo_espera));
    }else{
        $maior_tempo_espera = gmdate('H:i:s', $maior_tempo_espera);
    }

    if ($max_tempo_desistencia > $maior_tempo_espera) {
        $maior_tempo = $max_tempo_desistencia;
    } else {
        $maior_tempo = $maior_tempo_espera;
    }
}

// Monta o array de dados a serem retornados
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
    't' => $total,
    'tp' => $total_perdidas,
    'mt' => $maior_tempo
);

// Converte o array em formato JSON e retorna como resposta
echo json_encode($data);