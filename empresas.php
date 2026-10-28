<?php
session_start();
include_once("conexao.php");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>CRUD Simples - Empresas</title>
</head>
<body>
    <a href="index.php">Ver Pessoas</a> | 
    <a href="cargos.php">Ver Cargos</a> | 
    <a href="empresa_cadastrar.php">Cadastrar Nova Empresa</a><br><br>

    <h1>Lista de Empresas</h1>

    <?php
    if (isset($_SESSION['msg'])) {
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
    ?>

    <table border="1" cellpadding="5">
        <thead>
            <tr>
                <th>ID</th>
                <th>Razão Social</th>
                <th>Nome Fantasia</th>
                <th>CNPJ</th>
                <th>Telefone</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result_empresas = "SELECT * FROM empresas ORDER BY id DESC";
            $resultado_empresas = mysqli_query($conn, $result_empresas);

            while ($row = mysqli_fetch_assoc($resultado_empresas)) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['razao_social'] . "</td>";
                echo "<td>" . $row['nome_fantasia'] . "</td>";
                echo "<td>" . $row['cnpj'] . "</td>";
                echo "<td>" . $row['telefone'] . "</td>";
                echo "<td>
                        <a href='empresa_editar.php?id=" . $row['id'] . "'>Editar</a> | 
                        <a href='empresa_excluir.php?id=" . $row['id'] . "' onclick=\"return confirm('Deseja apagar esta empresa?');\">Apagar</a>
                      </td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>