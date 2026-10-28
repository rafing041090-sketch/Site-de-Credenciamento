<?php
session_start();
include_once("conexao.php");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>CRUD Simples - Cargos</title>
</head>
<body>
    <a href="index.php">Ver Pessoas</a> | 
    <a href="empresas.php">Ver Empresas</a> | 
    <a href="cargo_cadastrar.php">Cadastrar Novo Cargo</a><br><br>

    <h1>Lista de Cargos</h1>

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
                <th>Nome do Cargo</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result_cargos = "SELECT * FROM cargos ORDER BY id DESC";
            $resultado_cargos = mysqli_query($conn, $result_cargos);

            while ($row = mysqli_fetch_assoc($resultado_cargos)) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['nome'] . "</td>";
                echo "<td>
                        <a href='cargo_excluir.php?id=" . $row['id'] . "' onclick=\"return confirm('Deseja apagar este cargo?');\">Apagar</a>
                      </td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>