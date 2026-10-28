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
$email = '';
$categoria_id = '1';
$empresa_id = '';

if ($id > 0 && $db) {
    $stmt = $db->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        $nome = $row['nome'] ?? '';
        $email = $row['email'] ?? '';
        $categoria_id = $row['categoria_id'] ?? '1';
        $empresa_id = $row['empresa_id'] ?? '';
    }
}

$categorias = $db ? $db->query("SELECT id, nome FROM categorias ORDER BY id ASC") : null;
$empresas = $db ? $db->query("SELECT * FROM empresas ORDER BY id DESC") : null;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">

<div class="container" style="max-width: 650px;">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white fw-bold text-uppercase">
            <?= $id > 0 ? 'Editar Usuário' : 'Novo Usuário' ?>
        </div>
        <div class="card-body">
            <form action="usuario_salvar.php" method="POST">
                <input type="hidden" name="id" value="<?= $id ?>">

                <div class="mb-3">
                    <label for="categoria_id" class="form-label">Categoria:</label>
                    <select class="form-select" id="categoria_id" name="categoria_id" required>
                        <?php if ($categorias && $categorias->num_rows > 0): ?>
                            <?php while ($cat = $categorias->fetch_assoc()): ?>
                                <option value="<?= $cat['id'] ?>" <?= $categoria_id == $cat['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['nome']) ?>
                                </option>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <option value="1" <?= $categoria_id == 1 ? 'selected' : '' ?>>Administrador</option>
                            <option value="2" <?= $categoria_id == 2 ? 'selected' : '' ?>>Funcionário</option>
                            <option value="3" <?= $categoria_id == 3 ? 'selected' : '' ?>>Expositor</option>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="nome" class="form-label">Nome:</label>
                    <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($nome) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">E-mail:</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="senha" class="form-label">Senha <?= $id > 0 ? '(deixe em branco para manter a atual)' : '' ?>:</label>
                    <input type="password" class="form-control" id="senha" name="senha" <?= $id == 0 ? 'required' : '' ?>>
                </div>

                <div class="mb-3">
                    <label for="empresa_id" class="form-label">Empresa (Apenas para Expositores):</label>
                    <select class="form-select" id="empresa_id" name="empresa_id">
                        <option value="">Nenhuma / Não se aplica</option>
                        <?php if ($empresas): ?>
                            <?php while ($e = $empresas->fetch_assoc()): ?>
                                <?php $nome_empresa = $e['nome'] ?? $e['razao_social'] ?? 'Empresa ' . $e['id']; ?>
                                <option value="<?= $e['id'] ?>" <?= $empresa_id == $e['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($nome_empresa) ?>
                                </option>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="d-flex gap-2 pt-2">
                    <button type="submit" class="btn btn-primary">Salvar</button>
                    <a href="layout.php?pagina=usuarios" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>