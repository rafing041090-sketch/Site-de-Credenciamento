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
    $res = $db->query("SELECT * FROM empresas WHERE id = {$id}");
    $dadosExcluidos = formatarDadosLog(($res && $row = $res->fetch_assoc()) ? $row : []);

    $db->query("UPDATE usuarios SET empresa_id = NULL WHERE empresa_id = {$id}");
    $db->query("UPDATE pessoas SET empresa_id = NULL WHERE empresa_id = {$id}");

    $stmt = $db->prepare("DELETE FROM empresas WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    if (!empty($dadosExcluidos)) {
        registrarLog('EXCLUSAO', 'EMPRESAS', '', $dadosExcluidos);
    }
}

header("Location: layout.php?pagina=empresas");
exit;