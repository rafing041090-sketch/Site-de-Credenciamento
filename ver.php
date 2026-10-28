
<?php
require_once 'conexao.php';
$email = 'pablo@gmail.com';
$res = $conexao->query("SELECT id, email, senha FROM usuarios WHERE email = '$email'");
$user = $res->fetch_assoc();
echo "No banco:<br>";
var_dump($user);
echo "<br><br>md5 de 123: " . md5('123');
echo "<br>md5 de 123456: " . md5('123456');
echo "<br>md5 de pablo: " . md5('pablo');
?>