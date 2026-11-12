<?php
require_once __DIR__.'/conexao.php';
$db = $conexao;

$tipos = [
  'comissão' => 2, 'comissao' => 2,
  'casa cultural' => 3,
  'outros' => 4,
  'imprensa' => 7,
  'prefeitura de ijuí' => 6, 'prefeitura de ijui' => 6,
  'ueti / expofest' => 12,
  'show' => 13,
  'empresa' => 10,
  'expositor' => 11
];

$msg = '';
if($_SERVER['REQUEST_METHOD']=='POST' && isset($_FILES['arquivo'])){
  $tmp = $_FILES['arquivo']['tmp_name'];
  $linhas = file($tmp, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  if($linhas){
    $cab = array_map('trim', explode(';', array_shift($linhas)));
    $cab = array_map('strtolower', $cab); 

    $ok=0; $erro=0;
    foreach($linhas as $linha){
      $dados = array_map('trim', explode(';', $linha));
      if(count($dados) < 1) continue;
     $dados = array_slice(array_pad($dados, count($cab), ''), 0, count($cab));
     $reg = array_combine($cab, $dados);}
      $nome_fantasia = $db->real_escape_string($reg['nome_fantasia']?? '');
      $razao = $db->real_escape_string($reg['razao_social']?? '');
      $tipo_nome = strtolower(trim($reg['tipo_cadastro']?? 'empresa'));
      $tipo_id = $tipos[$tipo_nome]?? 11; // Expositor padrão

      if($nome_fantasia=='') continue;

      $sql = "INSERT INTO empresas (nome_fantasia, razao_social, tipo_cadastro, cnpj) VALUES ('$nome_fantasia', '$razao', '$tipo_id', '')";
      if(!$db->query($sql)){
        $sql2 = "INSERT INTO empresas (nome_fantasia, razao_social, tipo_id) VALUES ('$nome_fantasia', '$razao', '$tipo_id')";
        if(!$db->query($sql2)) $erro++; else $ok++;
      } else $ok++;
    }
    $msg = "<div class='alert alert-success'>Importação concluída: $ok importadas, $erro erros</div>";
  }
}
?>
<div class="container mt-3">
  <h4>Importar Empresas</h4>
  <?= $msg?>
  <a href="layout.php?pagina=empresas" class="btn btn-secondary btn-sm mb-3">Voltar</a>
  <?php if($msg):?><script>setTimeout(()=>window.location='layout.php?pagina=empresas', 2000), 2000</script><?php endif;?>
</div>