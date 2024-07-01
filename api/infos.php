<?php
    header('Content-Type: application/json');

    require_once("../config/config.php");

    $servername = $config['database']['host'];
    $username = $config['database']['databaseUser'];
    $password = $config['database']['databasePass'];
    $dbname = $config['database']['database'];

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Falha na conexão: " . $conn->connect_error);
    }

    $retObj = new stdClass();

    $recordQuery = "SELECT MAX(record) as r FROM server_record";
    $recordPlayers = $conn->query($recordQuery);
    if ($recordPlayers) {
        $rowPlayers = $recordPlayers->fetch_assoc();
        $retObj->onlineRecord = $rowPlayers['r'];
    } else {
        $retObj->onlineRecord = 0;
    }
    
    if($config['status']['serverStatus_online'] == 1) {
        $retObj->status = "Online";
    } else {
        $retObj->status = "Offline";
    }

    $playersQuery = "SELECT * FROM players WHERE online > 0 ";
    $onlinePlayers = $conn->query($playersQuery);
    if ($onlinePlayers) {
        $retObj->onlinePlayers = $onlinePlayers->num_rows;
    } else {
        $retObj->onlinePlayers = 0;
    }

    echo json_encode($retObj);
?>