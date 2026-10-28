<?php
session_start();
require_once 'conexao.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$db = isset($conexao) ? $conexao : (isset($conn) ? $conn : null);
$id = intval($_POST['id'] ?? 0);
$nome = trim(strip_tags($_POST['nome'] ?? ''));

if (!empty($nome) && $db) {
    if ($id > 0) {
        $stmt = $db->prepare("UPDATE tipos SET nome = ? WHERE id = ?");
        $stmt->bind_param("si", $nome, $id);
    } else {
        $stmt = $db->prepare("INSERT INTO tipos (nome) VALUES (?)");
        $stmt->bind_param("s", $nome);
    }
    $stmt->execute();
    $_SESSION['sucesso'] = "Tipo salvo com sucesso!";
}

header("Location: layout.php?pagina=tipos");
exit;