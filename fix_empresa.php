<?php
require_once 'conexao.php';

echo "Colunas atuais:<br>";
$res = $conexao->query("SHOW COLUMNS FROM empresas");
while($row = $res->fetch_assoc()){
    echo $row['Field']." - ".$row['Type']."<br>";
}

$conexao->query("ALTER TABLE empresas ADD COLUMN telefone VARCHAR(20) NULL AFTER cnpj");
$conexao->query("ALTER TABLE empresas ADD COLUMN email VARCHAR(100) NULL AFTER telefone");
$conexao->query("ALTER TABLE empresas ADD COLUMN endereco VARCHAR(255) NULL AFTER email");

echo "<br><br>Tentou adicionar. Agora:<br>";
$res = $conexao->query("SHOW COLUMNS FROM empresas");
while($row = $res->fetch_assoc()){
    echo $row['Field']." - ".$row['Type']."<br>";
}

echo "<br>PRONTO! Pode apagar esse arquivo e testar cadastrar empresa de novo.";
?>