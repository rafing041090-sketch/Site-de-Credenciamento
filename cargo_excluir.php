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
    $db->query("UPDATE pessoas SET cargo_id = NULL WHERE cargo_id = {$id}");

    $stmt = $db->prepare("DELETE FROM cargos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $_SESSION['sucesso'] = "Cargo excluído com sucesso!";
}

header("Location: layout.php?pagina=cargos");
exit;