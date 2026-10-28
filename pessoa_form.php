<?php
require_once 'conexao.php';
date_default_timezone_set('America/Sao_Paulo');
$db = $conexao;

$id = intval($_GET['id'] ?? 0);
$pessoa = null;
if ($id > 0) {
    $stmt = $db->prepare("SELECT * FROM pessoas WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $pessoa = $stmt->get_result()->fetch_assoc();
}

$cat_id = $_SESSION['CATEGORIA_ID'] ?? $_SESSION['categoria_id'] ?? 1;
$is_admin = ($cat_id == 1);
$empresa_id_sessao = $_SESSION['EMPRESA_ID'] ?? $_SESSION['empresa_id'] ?? $_SESSION['usuario_empresa_id'] ?? 0;

$empresa_id_get = intval($_GET['empresa_id'] ?? 0);

$veio_da_empresa = ($empresa_id_get > 0 && $id == 0);
$empresa_id_check = $id > 0 ? intval($pessoa['empresa_id'] ?? 0) : $empresa_id_get;

if (!$is_admin && $empresa_id_sessao) {
    $empresa_id_check = intval($empresa_id_sessao);
    $veio_da_empresa = true;
}

$qtd = 0;
$limite_total = 6;
$info_limite = "";
if ($empresa_id_check > 0) {
    $sqlLimite = "SELECT e.QUANTIDADE_ESPACOS, t.limite_pessoas FROM empresas e LEFT JOIN tipos t ON t.id = e.tipo_id WHERE e.id = $empresa_id_check LIMIT 1";
    $resLim = $db->query($sqlLimite);
    if ($resLim && $rowLim = $resLim->fetch_assoc()) {
        $qtd_espacos = intval($rowLim['QUANTIDADE_ESPACOS'] ?? 1);
        if ($qtd_espacos <= 0) $qtd_espacos = 1;
        $lim_por = intval($rowLim['limite_pessoas'] ?? 6);
        if ($lim_por <= 0) $lim_por = 6;
        $limite_total = $qtd_espacos * $lim_por;
        $rq = $db->query("SELECT COUNT(*) as total FROM pessoas WHERE empresa_id = $empresa_id_check");
        $qtd = $rq ? intval($rq->fetch_assoc()['total']) : 0;
        if ($id == 0) {
            $info_limite = "$qtd de $limite_total vagas usadas";
        }
    }
}

$empresas = $db->query("SELECT id, NOME_FANTASIA, nome_fantasia FROM empresas ORDER BY NOME_FANTASIA");
$cargos = $db->query("SELECT id, nome FROM cargos ORDER BY nome");
?>
<h4><?= $id ? "Editar Pessoa" : "Nova Pessoa" ?> <?= $info_limite ? "<small class='text-muted'>- $info_limite</small>" : "" ?></h4>
<form action="pessoa_salvar.php" method="POST" enctype="multipart/form-data" class="card p-3 shadow-sm">
    <input type="hidden" name="id" value="<?= $id ?>">
    
    <?php if($veio_da_empresa): ?>
        <input type="hidden" name="empresa_id" value="<?= $empresa_id_check ?>">
    <?php endif; ?>

    <div class="mb-3">
        <label>Empresa</label>
        <select name="empresa_id" class="form-select" <?= $veio_da_empresa ? 'disabled' : '' ?> required>
            <option value="">Selecione</option>
            <?php while($e=$empresas->fetch_assoc()): 
                $nome = $e['NOME_FANTASIA'] ?? $e['nome_fantasia'] ?? '';
            ?>
            <option value="<?= $e['id'] ?>" <?= $empresa_id_check==$e['id']?'selected':'' ?>><?= htmlspecialchars($nome) ?> (ID <?= $e['id'] ?>)</option>
            <?php endwhile; ?>
        </select>
        <?php if($veio_da_empresa): ?>
            <small class="text-muted">Empresa vinculada automaticamente (veio da lista da empresa). Para trocar, edite a pessoa depois.</small>
        <?php endif; ?>
    </div>

    <div class="mb-3"><label>Nome</label><input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($pessoa['nome']??'') ?>" required></div>
    <div class="mb-3"><label>CPF</label><input type="text" name="cpf" class="form-control" value="<?= htmlspecialchars($pessoa['cpf']??'') ?>"></div>
    <div class="mb-3"><label>Cargo</label>
        <select name="cargo_id" class="form-select">
            <option value="">Selecione</option>
            <?php while($c=$cargos->fetch_assoc()): ?>
            <option value="<?= $c['id'] ?>" <?= ($pessoa['cargo_id']??0)==$c['id']?'selected':'' ?>><?= htmlspecialchars($c['nome']) ?></option>
            <?php endwhile; ?>
        </select>
    </div>
    <div class="mb-3"><label>Foto</label><input type="file" name="foto" class="form-control"></div>
    <button class="btn btn-primary">Salvar</button>
    <a href="layout.php?pagina=pessoas<?= $empresa_id_get ? '&empresa_id='.$empresa_id_get : '' ?>" class="btn btn-secondary">Voltar</a>
</form>