<?php
session_start();
require_once 'conexao.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$db = isset($conexao) ? $conexao : (isset($conn) ? $conn : null);
$id = intval($_GET['id'] ?? 0);

if ($id > 0 && $db) {
    $stmt = $db->prepare("DELETE FROM tipos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $_SESSION['sucesso'] = "Tipo excluído com sucesso!";
}

header("Location: layout.php?pagina=tipos");
exit;