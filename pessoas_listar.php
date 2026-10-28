<?php
require_once __DIR__ . '/conexao.php';
$db = $conexao;
$empresa_id = $_SESSION['usuario_empresa_id'] ?? $_SESSION['empresa_id'] ?? 0;
$categoria_id = $_SESSION['categoria_id'] ?? $_SESSION['usuario_categoria'] ?? 0;
$busca = trim($_GET['busca'] ?? '');
$empresa_filtro = intval($_GET['empresa_id'] ?? 0);
$where = [];
if ($categoria_id == 3 && $empresa_id) { $where[] = "p.empresa_id = ".intval($empresa_id); }
if ($empresa_filtro > 0) { $where[] = "p.empresa_id = $empresa_filtro"; }
if (!empty($busca)) { $b=$db->real_escape_string($busca); $where[]="(p.nome LIKE '%$b%' OR p.cpf LIKE '%$b%' OR c.nome LIKE '%$b%')"; }
$sql_where = count($where) ? "WHERE ".implode(" AND ", $where) : "";
$sql = "SELECT p.id, p.nome, p.cpf, p.foto, p.empresa_id, c.nome as cargo_nome FROM pessoas p LEFT JOIN cargos c ON p.cargo_id=c.id $sql_where ORDER BY p.id DESC";
$res = $db->query($sql);
$total = $res ? $res->num_rows : 0;
$limite_info="";
$id_para_limite=$empresa_filtro?:$empresa_id;
if($id_para_limite>0){
 $sqlL="SELECT e.QUANTIDADE_ESPACOS, t.limite_pessoas FROM empresas e LEFT JOIN tipos t ON t.id=e.tipo_id WHERE e.id=$id_para_limite LIMIT 1";
 $rL=$db->query($sqlL);
 if($rL && $rowL=$rL->fetch_assoc()){
  $qtd_esp=intval($rowL['QUANTIDADE_ESPACOS']??1); if($qtd_esp<=0)$qtd_esp=1;
  $lim_por=intval($rowL['limite_pessoas']??6); if($lim_por<=0)$lim_por=6;
  $limite_total=$qtd_esp*$lim_por;
  $qrReal=$db->query("SELECT COUNT(*) as q FROM pessoas WHERE empresa_id=$id_para_limite");
  $qtd_real=$qrReal?intval($qrReal->fetch_assoc()['q']):$total;
  $limite_info="$qtd_real de $limite_total pessoas ($qtd_esp esp x $lim_por)";
 }
}
?>
<div class="d-flex justify-content-between align-items-center mb-3">
 <div class="d-flex gap-2 align-items-center flex-wrap">
  <h5 class="m-0">Lista de Pessoas</h5><small class="text-muted">(<?= $total ?>)</small>
  <?php if($limite_info):?><span class="badge bg-info"><?= $limite_info ?></span><?php endif;?>
  <a href="layout.php?pagina=pessoa_form<?= $empresa_filtro?'&empresa_id='.$empresa_filtro:'' ?>" class="btn btn-primary btn-sm ms-2">Nova Pessoa</a>
 </div>
 <form method="GET" action="layout.php" class="d-flex gap-2">
  <input type="hidden" name="pagina" value="pessoas">
  <input type="text" name="busca" class="form-control form-control-sm" placeholder="Buscar..." value="<?= htmlspecialchars($busca) ?>">
  <button class="btn btn-sm btn-secondary">Buscar</button>
 </form>
</div>
<div class="card shadow-sm border-0">
 <div class="table-responsive">
  <table class="table table-hover mb-0">
   <thead class="table-light"><tr><th>ID</th><th>Nome</th><th>CPF</th><th>Cargo</th><th class="text-end">Ações</th></tr></thead>
   <tbody>
    <?php if($res && $total>0): while($row=$res->fetch_assoc()): ?>
    <tr>
     <td><?= $row['id'] ?></td>
     <td><?= htmlspecialchars($row['nome']) ?></td>
     <td><?= htmlspecialchars($row['cpf']??'-') ?></td>
     <td><span class="badge bg-secondary"><?= htmlspecialchars($row['cargo_nome']??'N/A') ?></span></td>
     <td class="text-end">
      <a href="cracha.php?id=<?= $row['id'] ?>" target="_blank" class="btn btn-sm btn-dark">Crachá</a>
      <a href="layout.php?pagina=pessoa_form&id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary">Editar</a>
      <a href="pessoa_excluir.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Excluir?')">Excluir</a>
     </td>
    </tr>
    <?php endwhile; else: ?>
    <tr><td colspan="5" class="text-center text-muted py-4">Nenhuma pessoa encontrada.</td></tr>
    <?php endif;?>
   </tbody>
  </table>
 </div>
</div>