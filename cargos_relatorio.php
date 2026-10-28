<?php require 'conexao.php';
$busca=$_GET['busca']??''; $res=$conexao->query("SELECT * FROM cargos WHERE nome LIKE '%".$conexao->real_escape_string($busca)."%' ORDER BY nome");
?>
<!DOCTYPE html><html><head><meta charset="utf-8"><title>Relatório Cargos</title>
<style>body{font-family:Arial;font-size:12px}table{width:100%;border-collapse:collapse}th,td{border:1px solid #000;padding:6px}th{background:#eee}</style></head><body>
<h2 style="text-align:center">Relatório de Cargos</h2>
<table><tr><th>Nome</th></tr><?php while($r=$res->fetch_assoc()): ?>
<tr><td><?= $r['nome'] ?></td></tr><?php endwhile;?></table>
<script>window.print()</script></body></html>