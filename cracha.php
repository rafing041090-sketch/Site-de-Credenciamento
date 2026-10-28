<?php
require_once __DIR__ . '/conexao.php';
$db = $conexao;
$id = intval($_GET['id'] ?? 0);
if ($id <=0) die("ID inválido");

$sql = "SELECT p.*, c.nome as cargo_nome, e.NOME_FANTASIA, e.nome_fantasia as nf2, e.razao_social 
        FROM pessoas p 
        LEFT JOIN cargos c ON c.id = p.cargo_id 
        LEFT JOIN empresas e ON e.id = p.empresa_id 
        WHERE p.id = $id LIMIT 1";
$res = $db->query($sql);
$p = $res->fetch_assoc();
if (!$p) die("Pessoa não encontrada");

$nome_empresa = $p['NOME_FANTASIA'] ?? $p['nf2'] ?? $p['razao_social'] ?? 'Empresa '.$p['empresa_id'];
$codigo = str_pad($p['empresa_id'], 6, '0', STR_PAD_LEFT) . str_pad($p['id'], 6, '0', STR_PAD_LEFT);
$qr_url = "https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=".urlencode($codigo);

$foto = $p['foto'] ? (file_exists("uploads/".$p['foto']) ? "uploads/".$p['foto'] : $p['foto']) : "https://via.placeholder.com/150?text=Foto";
?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Crachá - <?= htmlspecialchars($p['nome']) ?></title>
<style>
body{font-family:Arial; background:#f0f0f0; display:flex; justify-content:center; padding:20px}
.cracha{width:380px; background:white; border:3px solid #000; border-radius:15px; padding:20px; text-align:center}
.cracha img.foto{width:120px;height:120px;border-radius:50%;object-fit:cover;border:3px solid #000}
.cracha h2{margin:10px 0 5px}
.cracha .cargo{color:#555}
.cracha .empresa{font-weight:bold; margin:10px 0; font-size:18px}
</style>
</head>
<body>
<div class="cracha">
    <img class="foto" src="<?= $foto ?>">
    <h2><?= htmlspecialchars($p['nome']) ?></h2>
    <div class="cargo"><?= htmlspecialchars($p['cargo_nome'] ?? '') ?></div>
    <div class="empresa"><?= htmlspecialchars($nome_empresa) ?></div>
    <hr>
    <img src="<?= $qr_url ?>" alt="QRCODE"><br>
    <small style="letter-spacing:2px"><b><?= $codigo ?></b><br>ID Empresa: <?= str_pad($p['empresa_id'],6,'0',STR_PAD_LEFT) ?> | ID Pessoa: <?= str_pad($p['id'],6,'0',STR_PAD_LEFT) ?></small>
    <br><br>
    <button onclick="window.print()" style="padding:10px 20px">Imprimir</button>
</div>
</body>
</html>