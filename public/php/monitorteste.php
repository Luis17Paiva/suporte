<?php

require 'vendor/autoload.php';

use PAMI\Client\Impl\ClientImpl as PamiClient;
use PAMI\Message\Action\LogoffAction;
use PAMI\Message\Event\NewchannelEvent;
use PAMI\Message\Event\HangupEvent;
use PAMI\Message\Event\BridgeEnterEvent;
use PAMI\Message\Event\BridgeEvent;


header('Content-Type: text/html; charset=UTF-8');

// Defina o idioma para pt-BR
setlocale(LC_ALL, 'pt_BR.utf-8', 'Portuguese_Brazil.1252');

// Define o fuso horário
date_default_timezone_set('America/Sao_Paulo');


// Define o horário de início (8:00) e o horário de término (18:00)
global $horarioInicio, $horarioTermino;
$horarioInicio = '08:00:00';
$horarioTermino = '18:00:00';

// Carrega as variáveis de ambiente do arquivo .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

$managerHost = $_ENV['MANAGER_HOST'];
$managerPort = $_ENV['MANAGER_PORT'];
$managerUsername = $_ENV['MANAGER_USERNAME'];
$managerPassword = $_ENV['MANAGER_PASSWORD'];

// Conecta ao banco de dados
$dsn = "pgsql:host=10.0.0.18;port=5432;dbname=Central;user=postgres;password=L_s321123S_l";
$conn = new PDO($dsn);

// Opções de configuração para a conexão com o servidor AMI
$opcoes = array(
    'host' => $managerHost,
    'port' => $managerPort,
    'username' => $managerUsername,
    'secret' => $managerPassword,
    'connect_timeout' => 10000, // Valor em milissegundos
    'read_timeout' => 10000, // Valor em milissegundos
    'scheme' => 'tcp://'
);

// Instancia a conexão
$conexao = new PamiClient($opcoes);

// Função para fechar a conexão ao finalizar o script
function shutdown() {
    global $conexao;
    // Desloga do Asterisk
    $acao = new LogoffAction;
    $conexao->send($acao);
    // Fecha a conexão
    $conexao->close();
    echo "Conexão Fechada com Sucesso", PHP_EOL;
}

// Registra a função para ser executada no final do script
register_shutdown_function('shutdown');

// Listener para eventos
$conexao->registerEventListener(function ($event) use ($conn) {
    global $conexao;
    global $uniqueId;

    if ($event instanceof NewchannelEvent) {
        // Nova chamada iniciada
        $dataInclusao = date('Y-m-d');
        $uniqueId = $event->getUniqueID();
        $callerId = $event->getCallerIDNum();
        $Extension = $event->getExtension();
        $LinkedId = $event->getLinkedId();

        // Extrai a parte antes do ponto na coluna id_asterisk
        $idAsterisk = substr($uniqueId, 0, strpos($uniqueId, '.'));
        $LinkedId = substr($LinkedId, 0, strpos($LinkedId, '.'));

        $horarioInicio = '08:00:00';
        $horarioTermino = '18:00:00';

        $hora = date('H:i:s');
        // Verifica se o número de telefone tem mais de 7 dígitos (indicando uma ligação externa)
        if (strlen($callerId) > 7  && !(($callerId === '<unknown>' || htmlspecialchars_decode($callerId) === '<unknown>') && $Extension === 's')
        && $callerId != '+3031237744'){
            // Extrai a parte antes do ponto na coluna id_asterisk
            $idAsterisk = substr($uniqueId, 0, strpos($uniqueId, '.'));
            
            // Verificar se o evento já foi registrado
            $query = "SELECT id_asterisk FROM atendimentos WHERE id_asterisk = :uniqueId AND data_inclusao = :dataInclusao";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':uniqueId', $idAsterisk);
            $stmt->bindParam(':dataInclusao', $dataInclusao);
            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                // Insere dados na tabela de atendimentos
                $insertQuery = "INSERT INTO atendimentos (id_asterisk, ura, numero, hora_chamada, status, data_inclusao) 
                                VALUES (:id_asterisk, :ura, :numero, :hora_chamada, :status, :data_inclusao)";
                
                $insertStmt = $conn->prepare($insertQuery);
                $insertStmt->bindParam(':id_asterisk', $idAsterisk);
                $insertStmt->bindParam(':numero', $callerId);
                $insertStmt->bindParam(':hora_chamada', $hora);
                $ura = 'SUP';
                $insertStmt->bindParam(':ura', $ura);
                if ($hora >= $horarioInicio && $hora <= $horarioTermino) {
                    $status = 'EM ESPERA'; 
                } else {
                    $status = 'FORA DO HORÁRIO'; 
                }
                $insertStmt->bindParam(':status', $status);
                $dataInclusao = date('Y-m-d');
                $insertStmt->bindParam(':data_inclusao', $dataInclusao);
                
                $insertStmt->execute();
            }
            
        }else if(substr($callerId, 0, 1) === "2"){
            echo "ID: $LinkedId -  CallerId: $callerId\n"; 
            // Identifica se ligação foi para o administrativo
            $dataInclusao = date('Y-m-d');

            $updateQuery = "UPDATE atendimentos SET ura = :ura
                            WHERE id_asterisk = :uniqueId AND data_inclusao = :dataInclusao";
            $updateStmt = $conn->prepare($updateQuery);
            $ura = 'ADM';
            $updateStmt->bindParam(':ura', $ura);
            $updateStmt->bindParam(':uniqueId', $LinkedId);
            $updateStmt->bindParam(':dataInclusao', $dataInclusao);
            $updateStmt->execute();

        }
        $teste = substr($callerId, 0, 1);
        echo "teste: $teste\n";

    } elseif ($event instanceof BridgeEnterEvent) {
        // Chamada atendida
        $uniqueId = $event->getUniqueID();
        $dataInclusao = date('Y-m-d');
        $hora = date('H:i:s');

        // Extrai a parte antes do ponto (se houver) na coluna id_asterisk
        $idAsterisk = substr($uniqueId, 0, strpos($uniqueId, '.'));

        $atendendoRamal = $event->getCallerIDNum();
  
        // Verificar se o evento existe no banco
        $query = "SELECT id_asterisk FROM atendimentos WHERE id_asterisk = :uniqueId";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':uniqueId', $idAsterisk);
        //$stmt->bindParam(':dataInclusao', $dataInclusao);
        $stmt->execute();

        if ($stmt->rowCount() != 0) {
            $callerId = $event->getCallerIDNum();
            $ramal = $event->getConnectedLineNum();
            
            if(!(strlen($ramal) > 7)) {
                $updateQuery = "UPDATE atendimentos SET status = :novoStatus, hora_atendimento = :hora, id_ramal = :ramal 
                                WHERE id_asterisk = :uniqueId 
                                AND NOT status = 'EM ATENDIMENTO - AGUARDANDO DESLIGAMENTO' AND data_inclusao = :dataInclusao";

                $novoStatus = 'EM ATENDIMENTO - AGUARDANDO DESLIGAMENTO'; 
                $updateStmt = $conn->prepare($updateQuery);
                $updateStmt->bindParam(':hora', $hora);
                $updateStmt->bindParam(':novoStatus', $novoStatus);
                $updateStmt->bindParam(':uniqueId', $idAsterisk);
                $updateStmt->bindParam(':ramal', $ramal);
                $updateStmt->bindParam(':dataInclusao', $dataInclusao);
                $updateStmt->execute();
            }
            // Verifica se a ligação foi transferida para outro ramal
            $query = "SELECT id_ramal FROM atendimentos WHERE id_asterisk = :uniqueId AND data_inclusao = :dataInclusao";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':uniqueId', $idAsterisk);
            $stmt->bindParam(':dataInclusao', $dataInclusao);
            $stmt->execute();

            // Obtém o resultado da consulta
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                $idRamalDoBanco = $result['id_ramal'];

                if ($idRamalDoBanco == $atendendoRamal && $idRamalDoBanco != $ramal) {
                    if(substr($ramal, 0, 1) === "2"){
                        $ura = 'ADM';
                    } else {
                        $ura = 'SUP';
                    }
                    $updateQuery = "UPDATE atendimentos SET id_ramal = :ramal, ura = :ura
                                WHERE id_asterisk = :uniqueId AND data_inclusao = :dataInclusao";
                    $updateStmt = $conn->prepare($updateQuery);
                    $updateStmt->bindParam(':ramal', $ramal);
                    $updateStmt->bindParam(':ura', $ura);
                    $updateStmt->bindParam(':uniqueId', $idAsterisk);
                    $updateStmt->bindParam(':dataInclusao', $dataInclusao);
                    $updateStmt->execute();
                    echo "Ramal: $idRamalDoBanco transferiu para o ramal: $ramal\n";
                }
            }
        }
    }elseif ($event instanceof HangupEvent) {
        // Chamada encerrada
        $uniqueId = $event->getUniqueID();

        // Extrai a parte antes do ponto (se houver) na coluna id_asterisk
        $idAsterisk = substr($uniqueId, 0, strpos($uniqueId, '.'));

        // Verificar se o evento existe no banco
        $query = "SELECT id_asterisk FROM atendimentos WHERE id_asterisk = :uniqueId";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':uniqueId', $idAsterisk);
        //$stmt->bindParam(':dataInclusao', $dataInclusao);
        $stmt->execute();

        if ($stmt->rowCount() != 0) {
            $callerId = $event->getCallerIDNum();
            $hora = date('H:i:s');
            $dataInclusao = date('Y-m-d');


            // Verifica se a ligação teve tempo sufiente para entrar na URA
            $query = "SELECT MAX(EXTRACT(EPOCH FROM :hora - hora_chamada)) AS tempo FROM atendimentos 
                        WHERE id_asterisk = :uniqueId AND data_inclusao = :dataInclusao";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':uniqueId', $idAsterisk);
            $stmt->bindParam(':hora', $hora);
            $stmt->bindParam(':dataInclusao', $dataInclusao);
            $stmt->execute();

            // Obtém o resultado da consulta
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if($result['tempo'] > 15){
                // Verifica se a ligação foi atendida
                $query = "SELECT hora_atendimento FROM atendimentos WHERE id_asterisk = :uniqueId AND data_inclusao = :dataInclusao";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(':uniqueId', $idAsterisk);
                $stmt->bindParam(':dataInclusao', $dataInclusao);
                $stmt->execute();

                // Obtém o resultado da consulta
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                $hora_atendimento_banco = $result['hora_atendimento'];

                if($hora_atendimento_banco === null){
                    $novoStatus = 'PERDIDO';
                }else{
                    $novoStatus = 'FINALIZADO';
                }

                // Extrai a parte antes do ponto (se houver) na coluna id_asterisk
                $idAsterisk = substr($uniqueId, 0, strpos($uniqueId, '.'));

                $updateQuery = "UPDATE atendimentos SET status = :novoStatus, hora_desliga = :hora 
                                WHERE id_asterisk = :uniqueId AND data_inclusao = :dataInclusao";
                $updateStmt = $conn->prepare($updateQuery);
                $updateStmt->bindParam(':uniqueId', $idAsterisk);
                $updateStmt->bindParam(':novoStatus', $novoStatus);
                $updateStmt->bindParam(':hora', $hora);
                $updateStmt->bindParam(':dataInclusao', $dataInclusao);
                $updateStmt->execute();

            } else {
                $novoStatus = 'N/A URA';
                // Extrai a parte antes do ponto (se houver) na coluna id_asterisk
                $idAsterisk = substr($uniqueId, 0, strpos($uniqueId, '.'));

                $updateQuery = "UPDATE atendimentos SET status = :novoStatus, hora_desliga = :hora 
                                WHERE id_asterisk = :uniqueId AND hora_desliga IS NULL AND data_inclusao = :dataInclusao";
                $updateStmt = $conn->prepare($updateQuery);
                $updateStmt->bindParam(':uniqueId', $idAsterisk);
                $updateStmt->bindParam(':novoStatus', $novoStatus);
                $updateStmt->bindParam(':hora', $hora);
                $updateStmt->bindParam(':dataInclusao', $dataInclusao);
                $updateStmt->execute();
            }
                
        }
    }
});

// Abre a conexão
$conexao->open();
echo "Conexão iniciada com Sucesso \r\n";

while (true) {
    $conexao->process();
    usleep(100000); // Aguarda 100ms
}
