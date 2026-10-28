<?php
session_start();
require_once 'conexao.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$db = isset($conexao) ? $conexao : (isset($conn) ? $conn : null);
$id = intval($_GET['id'] ?? $_POST['id'] ?? 0);

if ($id <= 0) {
    $_SESSION['erro'] = "ID inválido.";
    header("Location: layout.php?pagina=usuarios");
    exit;
}

if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $id) {
    $_SESSION['erro'] = "Você não pode excluir seu próprio usuário logado!";
    header("Location: layout.php?pagina=usuarios");
    exit;
}

try {
    $stmt = $db->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $_SESSION['sucesso'] = "Usuário excluído com sucesso!";
    } else {
        $_SESSION['erro'] = "Usuário não encontrado.";
    }

} catch (mysqli_sql_exception $e) {
    $_SESSION['erro'] = "Erro ao excluir: " . $e->getMessage();
}

header("Location: layout.php?pagina=usuarios");
exit;