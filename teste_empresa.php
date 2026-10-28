<?php
require_once __DIR__ . '/conexao.php';

echo "Conexao OK: " . ($conexao ? "SIM" : "NAO") . "<br>";

$sql = "INSERT INTO empresas (nome_fantasia, razao_social) VALUES ('TESTE','TESTE LTDA')";
if($conexao->query($sql)){
    echo "INSERIU! ID: " . $conexao->insert_id;
} else {
    echo "ERRO AO INSERIR: " . $conexao->error;
}

echo "<br><br>Listando:<br>";
$res = $conexao->query("SELECT * FROM empresas");
while($r = $res->fetch_assoc()){
    print_r($r); echo "<br>";
}