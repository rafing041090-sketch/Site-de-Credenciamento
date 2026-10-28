<?php
require 'conexao.php';
echo "<h3>Tabela empresas:</h3>";
$r = $conexao->query("SHOW COLUMNS FROM empresas");
while($c = $r->fetch_assoc()){
    echo $c['Field']." - ".$c['Type']."<br>";
}
echo "<h3>Tabela tipos:</h3>";
$r = $conexao->query("SHOW COLUMNS FROM tipos");
while($c = $r->fetch_assoc()){
    echo $c['Field']." - ".$c['Type']."<br>";
}
echo "<h3>Dados tipos:</h3>";
$r = $conexao->query("SELECT * FROM tipos");
while($c = $r->fetch_assoc()){
    print_r($c); echo "<br>";
}
?>