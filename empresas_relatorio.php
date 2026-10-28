<?php
require 'conexao.php';
$busca = $_GET['busca'] ?? '';
$busca_sql = $conexao->real_escape_string($busca);

$sql = "SELECT * FROM empresas 
        WHERE NOME_FANTASIA LIKE '%$busca_sql%' 
        OR RAZAO_SOCIAL LIKE '%$busca_sql%' 
        OR nome_fantasia LIKE '%$busca_sql%'
        OR razao_social LIKE '%$busca_sql%'
        ORDER BY 2";
$res = $conexao->query($sql);
if(!$res) die("Erro SQL: ".$conexao->error);
?>
<!DOCTYPE html>
<html><head><meta charset="utf-8"><title>Relatório Empresas</title>
<style>body{font-family:Arial;font-size:12px} table{width:100%;border-collapse:collapse} th,td{border:1px solid #000;padding:6px} th{background:#eee}</style></head><body>
<h2 style="text-align:center">Relatório de Empresas - Filtro: <?= htmlspecialchars($busca) ?></h2>
<p>Total: <?= $res->num_rows ?></p>
<table><tr><th>ID</th><th>Nome Fantasia</th><th>Razão Social</th><th>Tipo</th></tr>
<?php while($r=$res->fetch_assoc()): 
 $r = array_change_key_case($r, CASE_LOWER);
?>
<tr>
<td><?= $r['id'] ?></td>
<td><?= $r['nome_fantasia'] ?></td>
<td><?= $r['razao_social'] ?></td>
<td><?= $r['tipo_cadastro'] ?? $r['tipo_id'] ?? '' ?></td>
</tr>
<?php endwhile; ?>
</table>
<script>window.print()</script>
</body></html>