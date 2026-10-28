<?php
session_start();
include_once("conexao.php");

$busca = filter_input(INPUT_GET, 'busca', FILTER_SANITIZE_SPECIAL_CHARS);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>CRUD Simples - Pesquisar Pessoas</title>
</head>
<body>
    <a href="index.php">Voltar para a Lista Completa</a> | 
    <a href="cadastrar.php">Cadastrar Nova Pessoa</a><br><br>

    <h1>Pesquisar Pessoas</h1>

    <form method="GET" action="pesquisa.php">
        <label>Buscar por Nome ou CPF:</label><br>
        <input type="text" name="busca" placeholder="Digite o nome ou CPF..." value="<?php echo htmlspecialchars($busca ?? ''); ?>">
        <input type="submit" value="Pesquisar">
        <a href="pesquisa.php"><button type="button">Limpar</button></a>
    </form>

    <br><hr><br>

    <?php
    if (!empty($busca)) {
        $result_pesquisa = "SELECT p.*, e.nome_fantasia AS empresa_nome 
                            FROM pessoas p 
                            LEFT JOIN empresas e ON p.empresa_id = e.id 
                            WHERE p.nome LIKE '%$busca%' OR p.cpf LIKE '%$busca%' 
                            ORDER BY p.id DESC";
        $resultado_pesquisa = mysqli_query($conn, $result_pesquisa);

        $total_encontrado = mysqli_num_rows($resultado_pesquisa);
        echo "<p>Resultados encontrados: <strong>$total_encontrado</strong></p>";

        if ($total_encontrado > 0) {
            echo '<table border="1" cellpadding="5">';
            echo '<thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>Telefone</th>
                        <th>Empresa</th>
                        <th>Ações</th>
                    </tr>
                  </thead>';
            echo '<tbody>';

            while ($row = mysqli_fetch_assoc($resultado_pesquisa)) {
                echo '<tr>';
                echo '<td>' . $row['id'] . '</td>';
                echo '<td>' . $row['nome'] . '</td>';
                echo '<td>' . $row['cpf'] . '</td>';
                echo '<td>' . $row['telefone'] . '</td>';
                echo '<td>' . $row['empresa_nome'] . '</td>';
                echo '<td>
                        <a href="editar.php?id=' . $row['id'] . '">Editar</a> | 
                        <a href="excluir.php?id=' . $row['id'] . '" onclick="return confirm(\'Deseja apagar?\');">Apagar</a>
                      </td>';
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
        } else {
            echo '<p style="color:red;">Nenhum registo encontrado para a busca digitada.</p>';
        }
    } else {
        echo '<p>Digite um termo acima para iniciar a pesquisa.</p>';
    }
    ?>
</body>
</html>