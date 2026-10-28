<?php
require_once 'conexao.php';

$email = 'pablo@gmail.com';
$senha_digitada = 'pablo27092026';

$hash_atual = md5($senha_digitada);
echo "O que seu login está gerando agora: " . $hash_atual . "<br><br>";

$sql = "SELECT email, senha FROM usuarios WHERE email = '$email'";
$res = $conexao->query($sql);
if($row = $res->fetch_assoc()){
    echo "O que está salvo no banco: " . $row['senha'] . "<br><br>";
    
    if($row['senha'] === $hash_atual){
        echo "IGUAL - O problema não é a senha, é a query do seu index.php que está cortada na imagem.";
    } else {
        echo "DIFERENTE - É por isso que da incorreto. Seu banco tem um hash e seu código está gerando outro.";
    }
} else {
    echo "E-mail não encontrado! Verifique se a tabela é 'usuarios' mesmo.";
}
?>