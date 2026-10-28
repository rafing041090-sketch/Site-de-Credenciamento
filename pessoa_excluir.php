<?php
session_start();
require_once 'conexao.php';
require_once 'log_helper.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$db = isset($conexao) ? $conexao : (isset($conn) ? $conn : null);
$id = intval($_GET['id'] ?? 0);

if ($id > 0 && $db) {
    
    $res = $db->query("SELECT * FROM pessoas WHERE id = {$id}");
    $dadosExcluidos = formatarDadosLog(($res && $row = $res->fetch_assoc()) ? $row : []);

    $empresa_id_sessao = $_SESSION['usuario_empresa_id'] ?? $_SESSION['empresa_id'] ?? null;
    $categoria_id = $_SESSION['usuario_categoria'] ?? $_SESSION['categoria_id'] ?? 1;

    if ($categoria_id == 3 && !empty($empresa_id_sessao)) {
        $stmt = $db->prepare("DELETE FROM pessoas WHERE id = ? AND empresa_id = ?");
        $stmt->bind_param("ii", $id, $empresa_id_sessao);
    } else {
        $stmt = $db->prepare("DELETE FROM pessoas WHERE id = ?");
        $stmt->bind_param("i", $id);
    }

    $stmt->execute();

    if (!empty($dadosExcluidos)) {
        registrarLog('EXCLUSAO', 'PESSOAS', '', $dadosExcluidos);
    }
}

header("Location: layout.php?pagina=pessoas");
exit;