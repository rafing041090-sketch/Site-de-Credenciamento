<?php
session_start();
include_once("conexao.php");

$id         = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
$nome       = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
$cpf        = filter_input(INPUT_POST, 'cpf', FILTER_SANITIZE_SPECIAL_CHARS);
$telefone   = filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_SPECIAL_CHARS);
$empresa_id = filter_input(INPUT_POST, 'empresa_id', FILTER_SANITIZE_NUMBER_INT);

$result_pessoa = "UPDATE pessoas 
                  SET nome='$nome', cpf='$cpf', telefone='$telefone', empresa_id='$empresa_id', atualizado_em=NOW() 
                  WHERE id='$id'";
$resultado_pessoa = mysqli_query($conn, $result_pessoa);

if (mysqli_affected_rows($conn)) {
    $_SESSION['msg'] = "<p style='color:green;'>Pessoa atualizada com sucesso!</p>";
    header("Location: index.php");
} else {
    $_SESSION['msg'] = "<p style='color:red;'>Erro ou nenhuma alteração realizada!</p>";
    header("Location: editar.php?id=$id");
}
?>