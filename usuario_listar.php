<?php
require_once __DIR__ . '/conexao.php';
$db = $conexao;

$busca = trim($_GET['busca'] ?? '');
$sql = "SELECT u.id, u.nome, u.email, c.nome AS categoria 
        FROM usuarios u 
        LEFT JOIN categorias c ON u.categoria_id = c.id";

if (!empty($busca)) {
    $sql .= " WHERE u.nome LIKE '%" . $conexao->real_escape_string($busca) . "%'";
}
$sql .= " ORDER BY u.id DESC";

$result = $conexao->query($sql);
$total = $result ? $result->num_rows : 0;
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <a href="usuario_form.php" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Novo Usuário</a>
    
    <form method="GET" action="layout.php" class="d-flex gap-2">
        <input type="hidden" name="pagina" value="usuarios">
        <input type="text" name="busca" class="form-control form-control-sm" placeholder="Pesquisar..." value="<?= htmlspecialchars($busca) ?>">
        <button type="submit" class="btn btn-sm btn-secondary"><i class="bi bi-search"></i></button>
        <?php if (!empty($busca)): ?>
            <a href="layout.php?pagina=usuarios" class="btn btn-sm btn-outline-secondary">Limpar</a>
        <?php endif; ?>
    </form>
</div>

<div class="table-responsive bg-white rounded shadow-sm border">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>E-mail</th>
                <th>Categoria</th>
                <th class="text-end">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($total > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['nome']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['categoria'] ?? '-') ?></td>
                        <td class="text-end">
                            <a href="usuario_form.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <a href="usuario_excluir.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Confirmar exclusão?');"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5" class="text-center text-muted py-3">Nenhum usuário encontrado.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>