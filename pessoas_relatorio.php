<?php
require 'conexao.php';
$busca = $_GET['busca'] ?? '';
$empresa_id = intval($_GET['empresa_id'] ?? 0);

$where = "WHERE p.NOME LIKE '%".$conexao->real_escape_string($busca)."%'";
if($empresa_id > 0) $where .= " AND p.empresa_id = $empresa_id";

$sql = "SELECT p.NOME as pessoa_nome, p.CPF, e.NOME_FANTASIA as empresa, c.NOME as cargo 
        FROM pessoas p 
        LEFT JOIN empresas e ON e.ID = p.empresa_id 
        LEFT JOIN cargos c ON c.ID = p.cargo_id 
        $where ORDER BY p.NOME";
$res = $conexao->query($sql);
if(!$res) die("Erro SQL: ".$conexao->error);
?>
<!DOCTYPE html>
<html><head><meta charset="utf-8"><title>Relatório Pessoas</title>
<style>
 body{font-family:Arial;font-size:12px} table{width:100%;border-collapse:collapse;margin-top:10px}
 th,td{border:1px solid #000;padding:6px;text-align:left} th{background:#eee}
 @media print{.no-print{display:none}}
</style></head><body>
<h2 style="text-align:center">Relatório de Pessoas</h2>
<p>Filtro: <b><?= htmlspecialchars($busca) ?></b> | Empresa ID: <b><?= $empresa_id ?></b> | Total: <?= $res->num_rows ?></p>
<table><tr><th>Nome</th><th>CPF</th><th>Empresa</th><th>Cargo</th></tr>
<?php while($r=$res->fetch_assoc()): ?>
<tr><td><?= $r['pessoa_nome'] ?></td><td><?= $r['CPF'] ?></td><td><?= $r['empresa'] ?></td><td><?= $r['cargo'] ?></td></tr>
<?php endwhile; ?>
</table>
<br><center class="no-print"><button onclick="window.print()">Salvar em PDF / Imprimir</button></center>
<script>window.onload = () => { window.print(); }</script>
</body></html>