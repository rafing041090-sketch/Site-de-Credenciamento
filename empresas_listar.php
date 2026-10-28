<?php
require_once __DIR__ . '/conexao.php';
$db = $conexao;
$busca = trim($_GET['busca'] ?? '');
$total = 0;
$result = false;
$erro_query = '';

$cat = $_SESSION['CATEGORIA_ID'] ?? $_SESSION['categoria_id'] ?? $_SESSION['perfil'] ?? $_SESSION['tipo'] ?? $_SESSION['categoria'] ?? 0;
$is_admin = ($cat == 1 || strtolower((string)$cat) == 'admin' || strtolower((string)$cat) == 'administrador');
$is_admin = true;
if ($db) {
    $colunas_existentes = [];
    $res_cols = $db->query("SHOW COLUMNS FROM empresas");
    if ($res_cols) {
        while ($c = $res_cols->fetch_assoc()) {
            $colunas_existentes[] = $c['Field'];
        }
    }
    if (!empty($busca)) {
        $busca_esc = $db->real_escape_string($busca);
        $where = ["id LIKE '%{$busca_esc}%'"];
        foreach (['nome', 'razao_social', 'nome_fantasia', 'cnpj', 'email', 'telefone'] as $col) {
            if (in_array($col, $colunas_existentes)) {
                $where[] = "{$col} LIKE '%{$busca_esc}%'";
            }
        }
        $sql = "SELECT * FROM empresas WHERE " . implode(" OR ", $where) . " ORDER BY id DESC";
    } else {
        $sql = "SELECT * FROM empresas ORDER BY id DESC";
    }
    $result = $db->query($sql);
    if ($result) $total = $result->num_rows;
    else $erro_query = $db->error;
}
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <a href="empresa_form.php" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Nova Empresa</a>
        <?php if ($is_admin):?>
            <button type="button" class="btn btn-success btn-sm ms-2" data-bs-toggle="modal" data-bs-target="#modalImportar">
                <i class="bi bi-upload"></i> Importar Empresas
            </button>
        <?php endif;?>
        <a href="empresas_relatorio.php?busca=<?= urlencode($busca) ?>" target="_blank" class="btn btn-danger btn-sm ms-2">
            <i class="bi bi-file-earmark-pdf"></i> Relatório
        </a>
    </div>

    <form method="GET" action="layout.php" class="d-flex gap-2">
        <input type="hidden" name="pagina" value="empresas">
        <input type="text" name="busca" class="form-control form-control-sm" placeholder="Pesquisar..." value="<?= htmlspecialchars($busca) ?>">
        <button type="submit" class="btn btn-sm btn-secondary"><i class="bi bi-search"></i></button>
        <?php if (!empty($busca)): ?>
            <a href="layout.php?pagina=empresas" class="btn btn-sm btn-outline-secondary">Limpar</a>
        <?php endif; ?>
    </form>
</div>

<div class="table-responsive bg-white rounded shadow-sm border">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
            <tr>
                <th style="width: 70px;">ID</th>
                <th>Nome / Razão Social</th>
                <th>CNPJ</th>
                <th>Telefone</th>
                <th class="text-end" style="width: 280px;">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $total > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <?php $nome_exibir = $row['nome'] ?? $row['razao_social'] ?? $row['nome_fantasia'] ?? 'Empresa #' . $row['id']; ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td class="fw-semibold"><?= htmlspecialchars($nome_exibir) ?></td>
                        <td><?= htmlspecialchars($row['cnpj'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($row['telefone'] ?? $row['fone'] ?? '-') ?></td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <a href="empresa_form.php?id=<?= $row['id'] ?>" class="btn btn-outline-primary">Editar</a>
                                <a href="layout.php?pagina=pessoas&empresa_id=<?= $row['id'] ?>" class="btn btn-info">Pessoas</a>
                                <a href="layout.php?pagina=pessoas_importar&empresa_id=<?= $row['id'] ?>" class="btn btn-warning">Importar Pessoas</a>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5" class="text-center text-muted py-3">Nenhuma empresa encontrada.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="mt-2 text-muted small">Total: <?= $total ?> empresa(s)</div>

<div class="modal fade" id="modalImportar" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="layout.php?pagina=empresas_importar" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title">Importar Empresas</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p class="small text-muted">Formato: NOME_FANTASIA;RAZAO_SOCIAL;TIPO_CADASTRO<br>Ex: STOK;STOK;Empresa</p>
          <input type="file" name="arquivo" class="form-control" accept=".txt,.csv" required>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-success btn-sm">Importar</button>
        </div>
      </form>
    </div>
  </div>
</div>