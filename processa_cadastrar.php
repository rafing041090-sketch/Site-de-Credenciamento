<?php
session_start();
include_once("conexao.php");

$nome       = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
$cpf        = filter_input(INPUT_POST, 'cpf', FILTER_SANITIZE_SPECIAL_CHARS);
$telefone   = filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_SPECIAL_CHARS);
$empresa_id = filter_input(INPUT_POST, 'empresa_id', FILTER_SANITIZE_NUMBER_INT);

$result_pessoa = "INSERT INTO pessoas (nome, cpf, telefone, empresa_id, criado_em) 
                  VALUES ('$nome', '$cpf', '$telefone', '$empresa_id', NOW())";
$resultado_pessoa = mysqli_query($conn, $result_pessoa);

if (mysqli_insert_id($conn)) {
    $_SESSION['msg'] = "<p style='color:green;'>Pessoa cadastrada com sucesso!</p>";
    header("Location: index.php");
} else {
    $_SESSION['msg'] = "<p style='color:red;'>Erro ao cadastrar pessoa!</p>";
    header("Location: cadastrar.php");
}
?>