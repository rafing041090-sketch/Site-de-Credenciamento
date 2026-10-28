<?php
session_start();
include_once("conexao.php");

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if (!empty($id)) {
    $result_pessoa = "DELETE FROM pessoas WHERE id='$id'";
    $resultado_pessoa = mysqli_query($conn, $result_pessoa);

    if (mysqli_affected_rows($conn)) {
        $_SESSION['msg'] = "<p style='color:green;'>Pessoa apagada com sucesso!</p>";
    } else {
        $_SESSION['msg'] = "<p style='color:red;'>Erro ao apagar registro!</p>";
    }
} else {
    $_SESSION['msg'] = "<p style='color:red;'>Selecione um registro válido!</p>";
}

header("Location: index.php");
exit;
?>