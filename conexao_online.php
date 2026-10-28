<?php
$servername = "mysql.escola25dejulho.com.br";
$username   = "escola25dejul164";
$password   = "turma421";
$schema     = "escola25dejul164";

$conexao = new mysqli($servername, $username, $password, $schema, 3306);

if ($conexao->connect_error) {
    die("Falha na Conexão: " . $conexao->connect_error);
}

$conexao->query("SET NAMES 'utf8'");
$conexao->query('SET character_set_connection=utf8');
$conexao->query('SET character_set_client=utf8');
$conexao->query('SET character_set_results=utf8');

$conn = $conexao;
?>