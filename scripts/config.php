<?php
/* Parametri da modificare */
$nome_gioco = "Prova";
$nome_cartella = "pms";
/* Parametri per database */
$db_host = "localhost";
$db_username = "root";
$db_password = 'tartassare';
$db_nome = 'pms';
/* Non modificare oltre questo punto!*/

function connetti() {
	global $db_host,$db_username,$db_password,$db_nome;
    try {
        $conn = new PDO("mysql:host=$db_host;dbname=$db_nome", $db_username, $db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        die("DB Error: " . $e->getMessage());
    }
}
?>