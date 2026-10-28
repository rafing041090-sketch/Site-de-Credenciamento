<?php
require_once 'conexao.php';
$email = 'pablo@gmail.com';
$nova_senha = '123';

$conexao->query("UPDATE usuarios SET senha = MD5('$nova_senha') WHERE email = '$email'");

if ($conexao->affected_rows > 0) {
    echo "Senha resetada! Agora loga com pablo@gmail.com / 123";
} else {
    echo "Não achou o email ou não alterou. Vamos ver o que tem:";
    $res = $conexao->query("SELECT id, email, senha FROM usuarios WHERE email = '$email'");
    var_dump($res->fetch_assoc());
}
?>