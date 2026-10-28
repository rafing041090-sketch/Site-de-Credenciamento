<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');
require_once 'conexao.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$db = isset($conexao) ? $conexao : (isset($conn) ? $conn : null);
$id = intval($_GET['id'] ?? 0);

$nome_fantasia      = '';
$razao_social       = '';
$cnpj               = '';
$telefone           = '';
$email              = '';
$tipo_id            = 0;
$quantidade_espacos = 0;

if ($id > 0 && $db) {
    $stmt = $db->prepare("SELECT * FROM empresas WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        $nome_fantasia      = $row['nome_fantasia'] ?? $row['NOME_FANTASIA'] ?? '';
        $razao_social       = $row['razao_social'] ?? $row['RAZAO_SOCIAL'] ?? '';
        $cnpj               = $row['cnpj'] ?? $row['CNPJ'] ?? '';
        $telefone           = $row['telefone'] ?? $row['TELEFONE'] ?? '';
        $email              = $row['email'] ?? $row['EMAIL'] ?? '';
        $tipo_id            = $row['tipo_id'] ?? $row['TIPO_ID'] ?? 0;
        $quantidade_espacos = $row['quantidade_espacos'] ?? $row['QUANTIDADE_ESPACOS'] ?? 0;
    }
}

$tipos = [];
if ($db) {
    $resTipos = $db->query("SELECT * FROM tipos ORDER BY nome ASC");
    if ($resTipos) {
        while ($t = $resTipos->fetch_assoc()) {
            $tipos[] = $t;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Empresa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">

<div class="container" style="max-width: 650px;">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white fw-bold text-uppercase">
            <?= $id > 0 ? 'Editar Empresa' : 'Nova Empresa' ?>
        </div>
        <div class="card-body">
            <form action="empresa_salvar.php" method="POST">
                <input type="hidden" name="id" value="<?= $id ?>">

               <div class="mb-3">
                   <label class="form-label">Nome Fantasia:</label>
                   <input type="text" class="form-control" name="nome_fantasia" value="<?= htmlspecialchars($nome_fantasia) ?>" required>
               </div>

               <div class="mb-3">
                   <label class="form-label">Razão Social:</label>
                   <input type="text" class="form-control" name="razao_social" value="<?= htmlspecialchars($razao_social) ?>" required>
               </div>

                <div class="mb-3">
                    <label class="form-label">CNPJ:</label>
                    <input type="text" class="form-control" name="cnpj" value="<?= htmlspecialchars($cnpj) ?>" placeholder="00.000.000/0001-00">
                </div>

                <div class="mb-3">
                    <label class="form-label">Tipo de Empresa:</label>
                    <select name="tipo_id" id="tipo_id" class="form-select" required onchange="toggleEspacos()">
                    <option value="">Selecione um tipo...</option>
                    <?php foreach ($tipos as $tipo): ?>
                    <option value="<?= $tipo['id'] ?>" data-controla="<?= $tipo['controla_espacos'] ?? 'N' ?>" <?= ($tipo_id == $tipo['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($tipo['nome']) ?>
                    </option>
                    <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3" id="box_espacos" style="display: none;">
                    <label class="form-label">Quantidade de Espaços:</label>
                    <input type="number" name="quantidade_espacos" id="quantidade_espacos" class="form-control" min="0" value="<?= htmlspecialchars($quantidade_espacos) ?>">
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Telefone:</label>
                        <input type="text" class="form-control" name="telefone" placeholder="(00) 00000-0000" value="<?= htmlspecialchars($telefone) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">E-mail:</label>
                        <input type="email" class="form-control" name="email" placeholder="empresa@email.com" value="<?= htmlspecialchars($email) ?>">
                    </div>
                </div>

                <div class="d-flex gap-2 pt-2">
                    <button type="submit" class="btn btn-primary">Salvar</button>
                    <a href="layout.php?pagina=empresas" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleEspacos() {
    var select = document.getElementById('tipo_id');
    var selectedOption = select ? select.options[select.selectedIndex] : null;
    var controla = selectedOption ? selectedOption.getAttribute('data-controla') : 'N';
    var box = document.getElementById('box_espacos');
    if (controla === 'S') {
        box.style.display = 'block';
    } else {
        box.style.display = 'none';
        document.getElementById('quantidade_espacos').value = 0;
    }
}
window.onload = toggleEspacos;
</script>

</body>
</html>