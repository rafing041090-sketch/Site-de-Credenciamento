<?php
session_start();
include_once("conexao.php");

$razao_social = filter_input(INPUT_POST, 'razao_social', FILTER_SANITIZE_SPECIAL_CHARS);
$nome_fantasia = filter_input(INPUT_POST, 'nome_fantasia', FILTER_SANITIZE_SPECIAL_CHARS);
$cnpj         = filter_input(INPUT_POST, 'cnpj', FILTER_SANITIZE_SPECIAL_CHARS);
$telefone     = filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_SPECIAL_CHARS);
$email        = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

$sql = "INSERT INTO empresas (razao_social, nome_fantasia, cnpj, telefone, email) 
        VALUES ('$razao_social', '$nome_fantasia', '$cnpj', '$telefone', '$email')";
$resultado = mysqli_query($conn, $sql);

if (mysqli_insert_id($conn)) {
    $_SESSION['msg'] = "<p style='color:green;'>Empresa cadastrada com sucesso!</p>";
    header("Location: empresas.php");
} else {
    $_SESSION['msg'] = "<p style='color:red;'>Erro ao cadastrar empresa!</p>";
    header("Location: empresa_cadastrar.php");
}
?>