<?php
$servername = "127.0.0.1";
$username   = "root";
$password   = "";
$schema     = "estagios25";

$conexao = new mysqli($servername, $username, $password, $schema, 3306);

if ($conexao->connect_error) {
    die("Falha na Conexão: " . $conexao->connect_error);
}

$conn = $conexao;
?>