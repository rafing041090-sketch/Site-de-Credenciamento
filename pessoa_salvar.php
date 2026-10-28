<?php
session_start();
require_once 'conexao.php';
require_once 'log_helper.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$db = $conexao ?? $conn ?? null;

$id         = intval($_POST['id'] ?? 0);
$nome       = trim(strip_tags($_POST['nome'] ?? ''));
$cpf        = trim(strip_tags($_POST['cpf'] ?? ''));
$cargo_id   = !empty($_POST['cargo_id']) ? intval($_POST['cargo_id']) : null;
$empresa_id = !empty($_POST['empresa_id']) ? intval($_POST['empresa_id']) : 0;
$ingresso_permanente = trim($_POST['ingresso_permanente'] ?? '');
$documento  = trim($_POST['documento'] ?? '');
$telefone   = trim($_POST['telefone'] ?? '');
$senha_admin_digitada = trim($_POST['senha_admin'] ?? '');

$categoria_id = $_SESSION['usuario_categoria'] ?? $_SESSION['categoria_id'] ?? $_SESSION['CATEGORIA_ID'] ?? 0;
$empresa_id_sessao = $_SESSION['usuario_empresa_id'] ?? $_SESSION['empresa_id'] ?? $_SESSION['EMPRESA_ID'] ?? 0;

if ($categoria_id == 3 && $empresa_id_sessao) {
    $empresa_id = intval($empresa_id_sessao);
}

if ($empresa_id > 0) {
    $check = $db->query("SELECT id, QUANTIDADE_ESPACOS, TIPO_ID FROM empresas WHERE id = $empresa_id");
    if ($check->num_rows == 0) {
        die("ERRO: Empresa não encontrada.");
    }
    $dados_empresa = $check->fetch_assoc();
} else {
    die("ERRO: empresa_id vazio. Faça logout e login novamente.");
}

$res_count = $db->query("SELECT COUNT(*) as total FROM pessoas WHERE empresa_id = " . intval($empresa_id) . " AND (excluido_em IS NULL OR excluido_em = '') AND id != $id");
$row_count = $res_count ? $res_count->fetch_assoc() : null;
$total = $row_count ? intval($row_count['total']) : 0;

$sql_limite = "SELECT t.limite_pessoas, e.QUANTIDADE_ESPACOS, e.quantidade_espacos FROM empresas e LEFT JOIN tipos t ON t.id = e.tipo_id WHERE e.id = $empresa_id LIMIT 1";
$res_limite = $db->query($sql_limite);
$limite_por_espaco = 6;
$qtd_espacos = 1;
if ($res_limite && $row_limite = $res_limite->fetch_assoc()) {
    $limite_por_espaco = intval($row_limite['limite_pessoas'] ?? 6);
    if ($limite_por_espaco <= 0) $limite_por_espaco = 6;
    $qtd_espacos = intval($row_limite['QUANTIDADE_ESPACOS'] ?? $row_limite['quantidade_espacos'] ?? 1);
    if ($qtd_espacos <= 0) $qtd_espacos = 1;
}
$limite_max = $qtd_espacos * $limite_por_espaco;

if ($id == 0 && $total >= $limite_max) {
    if ($categoria_id == 3) {
        die("Erro: Limite de pessoas atingido para esta empresa. Total: $total / Limite: $limite_max ($qtd_espacos espaços x $limite_por_espaco). Contate o administrador.");
    }
    if ($categoria_id == 2 || $categoria_id == 3) {
        if (empty($senha_admin_digitada)) {
            die("Erro: Limite de $limite_max pessoas atingido ($total cadastradas). É necessário informar a senha de administrador para liberar. <a href='javascript:history.back()'>Voltar</a>");
        }
        $senha_hash = md5($senha_admin_digitada);
        $checkAdmin = $db->query("SELECT ID FROM usuarios WHERE CATEGORIA_ID = 1 AND SENHA = '$senha_hash' LIMIT 1");
        if ($checkAdmin->num_rows == 0) {
            die("Erro: Senha de administrador incorreta! <a href='javascript:history.back()'>Voltar</a>");
        }
    }
}

$foto_nome = null;
if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
    $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
    $foto_nome = uniqid() . "." . $ext;
    if (!is_dir("uploads")) mkdir("uploads", 0777, true);
    move_uploaded_file($_FILES['foto']['tmp_name'], "uploads/" . $foto_nome);
}

if (empty($foto_nome) && $id > 0) {
    $res_foto = $db->query("SELECT foto FROM pessoas WHERE id = $id");
    if ($r = $res_foto->fetch_assoc()) {
        $foto_nome = $r['foto'];
    }
}

if (!empty($nome) && $db) {
    if ($id > 0) {
        if ($foto_nome) {
            $stmt = $db->prepare("UPDATE pessoas SET nome=?, cpf=?, cargo_id=?, empresa_id=?, documento=?, telefone=?, foto=? WHERE id=?");
            $stmt->bind_param("ssiisssi", $nome, $cpf, $cargo_id, $empresa_id, $documento, $telefone, $foto_nome, $id);
        } else {
            $stmt = $db->prepare("UPDATE pessoas SET nome=?, cpf=?, cargo_id=?, empresa_id=?, documento=?, telefone=? WHERE id=?");
            $stmt->bind_param("ssiissi", $nome, $cpf, $cargo_id, $empresa_id, $documento, $telefone, $id);
        }
        $stmt->execute();

        if (function_exists('writeRegistroLog')) {
            $user_id = $_SESSION['user_id'] ?? 1;
            writeRegistroLog("{$user_id};ALTERACAO;PESSOAS;{$id};");
        }
    } else {
        $stmt = $db->prepare("INSERT INTO pessoas (nome, cpf, cargo_id, empresa_id, documento, telefone, foto) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiisss", $nome, $cpf, $cargo_id, $empresa_id, $documento, $telefone, $foto_nome);
        $stmt->execute();
        $novo_id = $stmt->insert_id;

        if (function_exists('writeRegistroLog')) {
            $user_id = $_SESSION['user_id'] ?? 1;
            writeRegistroLog("{$user_id};INCLUSAO;PESSOAS;{$novo_id};");
        }
    }
    header("Location: layout.php?pagina=pessoas&msg=ok");
exit;
} else {
    die("Nome vazio ou erro de conexão");
}