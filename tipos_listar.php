<?php
require_once __DIR__ . '/conexao.php';
$db = $conexao;

$busca = trim($_GET['busca'] ?? '');
$sql = "SELECT * FROM tipos";

if (!empty($busca)) {
    $busca_esc = $db->real_escape_string($busca);
    $sql .= " WHERE nome LIKE '%{$busca_esc}%' OR id LIKE '%{$busca_esc}%'";
}
$sql .= " ORDER BY id DESC";

$result = $db->query($sql);
$total = $result ? $result->num_rows : 0;
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <a href="tipo_form.php" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Novo Tipo</a>
    
    <form method="GET" action="layout.php" class="d-flex gap-2">
        <input type="hidden" name="pagina" value="tipos">
        <input type="text" name="busca" class="form-control form-control-sm" placeholder="Pesquisar..." value="<?= htmlspecialchars($busca) ?>">
        <button type="submit" class="btn btn-sm btn-secondary"><i class="bi bi-search"></i></button>
        <?php if (!empty($busca)): ?>
            <a href="layout.php?pagina=tipos" class="btn btn-sm btn-outline-secondary">Limpar</a>
        <?php endif; ?>
    </form>
</div>

<div class="table-responsive bg-white rounded shadow-sm border">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
            <tr>
                <th style="width: 80px;">ID</th>
                <th>Nome do Tipo</th>
                <th style="width: 150px;" class="text-end">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $total > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['nome']) ?></td>
                        <td class="text-end">
                            <a href="tipo_form.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning" title="Editar"><i class="bi bi-pencil"></i></a>
                            <a href="tipo_excluir.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" title="Excluir"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="3" class="text-center text-muted py-3">Nenhum tipo encontrado.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="mt-2 text-muted small">Total: <?= $total ?> tipo(s)</div>