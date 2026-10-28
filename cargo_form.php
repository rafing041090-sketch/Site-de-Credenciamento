<?php
session_start();
require_once 'conexao.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$db = isset($conexao) ? $conexao : (isset($conn) ? $conn : null);
$id = intval($_GET['id'] ?? 0);
$nome = '';

if ($id > 0 && $db) {
    $stmt = $db->prepare("SELECT nome FROM cargos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        $nome = $row['nome'];
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Cargo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">

<div class="container" style="max-width: 500px;">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white fw-bold text-uppercase">
            <?= $id > 0 ? 'Editar Cargo' : 'Novo Cargo' ?>
        </div>
        <div class="card-body">
            <form action="cargo_salvar.php" method="POST">
                <input type="hidden" name="id" value="<?= $id ?>">

                <div class="mb-3">
                    <label for="nome" class="form-label">Nome do Cargo:</label>
                    <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($nome) ?>" required autofocus>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Salvar</button>
                    <a href="layout.php?pagina=cargos" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>