<?php
require 'conexao.php';
$db = $conexao ?? $conn;
echo "<pre>";
echo "SESSAO:\n";
session_start();
print_r($_SESSION);
echo "\n\nPESSOAS NO BANCO:\n";
$res = $db->query("SELECT id, nome, empresa_id FROM pessoas");
while($r = $res->fetch_assoc()){ print_r($r); }
echo "\n\nEMPRESAS:\n";
$res = $db->query("SELECT id, nome FROM empresas");
while($r = $res->fetch_assoc()){ print_r($r); }
?>