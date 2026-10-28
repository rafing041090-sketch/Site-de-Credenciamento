<?php
include_once("conexao.php");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>CRUD Simples - Cadastrar Pessoa</title>
</head>
<body>
    <a href="index.php">Voltar</a><br><br>
    <h1>Cadastrar Pessoa</h1>

    <form method="POST" action="processa_cadastrar.php">
        <label>Nome Completo: </label><br>
        <input type="text" name="nome" placeholder="Digite o nome" required><br><br>

        <label>CPF: </label><br>
        <input type="text" name="cpf" placeholder="Digite o CPF"><br><br>

        <label>Telefone: </label><br>
        <input type="text" name="telefone" placeholder="Digite o telefone"><br><br>

        <label>Empresa: </label><br>
        <select name="empresa_id" required>
            <option value="">Selecione a Empresa</option>
            <?php
            $result_empresas = "SELECT id, nome_fantasia FROM empresas ORDER BY nome_fantasia";
            $resultado_empresas = mysqli_query($conn, $result_empresas);
            while ($row_emp = mysqli_fetch_assoc($resultado_empresas)) {
                echo "<option value='" . $row_emp['id'] . "'>" . $row_emp['nome_fantasia'] . "</option>";
            }
            ?>
        </select><br><br>

        <input type="submit" value="Cadastrar">
    </form>
</body>
</html>