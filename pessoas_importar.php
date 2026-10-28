<?php
require_once __DIR__.'/conexao.php';
$db = $conexao;
$empresa_id = intval($_GET['empresa_id']?? 0);

$emp = $db->query("SELECT * FROM empresas WHERE id=$empresa_id LIMIT 1")->fetch_assoc();
$nome_emp = $emp['nome_fantasia']?? $emp['nome']?? 'Empresa '.$empresa_id;

$msg='';
if($_SERVER['REQUEST_METHOD']=='POST' && isset($_FILES['arquivo'])){
  $linhas = file($_FILES['arquivo']['tmp_name'], FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  $cab = array_map('trim', explode(';', array_shift($linhas)));
  $cab = array_map('strtolower', $cab);
  $ok=0;
  foreach($linhas as $l){
    $dados = array_map('trim', explode(';', $l));
    $reg = @array_combine($cab, $dados);
    if(!$reg) $reg = ['nome'=>$dados[0]??'', 'cpf'=>$dados[1]??'', 'cargo'=>$dados[2]??''];
    $nome = $db->real_escape_string($reg['nome']?? $dados[0]?? '');
    $cpf = $db->real_escape_string($reg['cpf']?? $reg['documento']?? $dados[1]?? '');
    if($nome=='') continue;
    $db->query("INSERT INTO pessoas (nome, cpf, empresa_id) VALUES ('$nome', '$cpf', $empresa_id)");
    $ok++;
  }
  $msg="<div class='alert alert-success'>$ok pessoas importadas para $nome_emp</div>";
}
?>
<div class="container mt-3">
  <h4>Importar Pessoas - <?= htmlspecialchars($nome_emp)?></h4>
  <?= $msg?>
  <form method="POST" enctype="multipart/form-data">
    <input type="file" name="arquivo" class="form-control mb-2" accept=".txt,.csv" required>
    <button class="btn btn-warning">Importar</button>
    <a href="layout.php?pagina=empresas" class="btn btn-secondary">Voltar</a>
  </form>
</div>