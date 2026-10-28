<?php
session_start();
include_once("conexao.php");

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$result_pessoa = "SELECT * FROM pessoas WHERE id = '$id'";
$resultado_pessoa = mysqli_query($conn, $result_pessoa);
$row_pessoa = mysqli_fetch_assoc($resultado_pessoa);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>CRUD Simples - Editar Pessoa</title>
</head>
<body>
    <a href="index.php">Voltar</a><br><br>
    <h1>Editar Pessoa</h1>

    <form method="POST" action="processa_editar.php">
        <input type="hidden" name="id" value="<?php echo $row_pessoa['id']; ?>">

        <label>Nome Completo: </label><br>
        <input type="text" name="nome" value="<?php echo $row_pessoa['nome']; ?>" required><br><br>

        <label>CPF: </label><br>
        <input type="text" name="cpf" value="<?php echo $row_pessoa['cpf']; ?>"><br><br>

        <label>Telefone: </label><br>
        <input type="text" name="telefone" value="<?php echo $row_pessoa['telefone']; ?>"><br><br>

        <label>Empresa: </label><br>
        <select name="empresa_id" required>
            <?php
            $result_empresas = "SELECT id, nome_fantasia FROM empresas ORDER BY nome_fantasia";
            $resultado_empresas = mysqli_query($conn, $result_empresas);
            while ($row_emp = mysqli_fetch_assoc($resultado_empresas)) {
                $selected = ($row_emp['id'] == $row_pessoa['empresa_id']) ? "selected" : "";
                echo "<option value='" . $row_emp['id'] . "' $selected>" . $row_emp['nome_fantasia'] . "</option>";
            }
            ?>
        </select><br><br>

        <input type="submit" value="Salvar Alterações">
    </form>
</body>
</html>