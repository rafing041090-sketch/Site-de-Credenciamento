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
$email = trim(strip_tags($_POST['email'] ?? ''));
$senha = trim($_POST['senha'] ?? '');
$categoria_id = intval($_POST['categoria_id'] ?? 1);
$empresa_id = !empty($_POST['empresa_id']) ? intval($_POST['empresa_id']) : null;

if (!in_array($categoria_id, [1, 2, 3])) {
    $categoria_id = 1;
}

if (!empty($nome) && !empty($email) && $db) {
    
    $check = $db->prepare("SELECT id FROM usuarios WHERE email = ? AND id != ? LIMIT 1");
    $check->bind_param("si", $email, $id);
    $check->execute();
    $res = $check->get_result();

    if ($res->num_rows > 0) {
        $_SESSION['erro'] = "Este e-mail '$email' já está cadastrado! Use outro e-mail.";
        header("Location: layout.php?pagina=usuarios");
        exit;
    }

    try {
        if ($id > 0) {
            if (!empty($senha)) {
                $senha_hash = md5($senha);
                $stmt = $db->prepare("UPDATE usuarios SET nome = ?, email = ?, senha = ?, categoria_id = ?, empresa_id = ? WHERE id = ?");
                $stmt->bind_param("sssiii", $nome, $email, $senha_hash, $categoria_id, $empresa_id, $id);
            } else {
                $stmt = $db->prepare("UPDATE usuarios SET nome = ?, email = ?, categoria_id = ?, empresa_id = ? WHERE id = ?");
                $stmt->bind_param("ssiii", $nome, $email, $categoria_id, $empresa_id, $id);
            }
        } else {
            $senha_hash = md5($senha ?: '123456');
            $stmt = $db->prepare("INSERT INTO usuarios (nome, email, senha, categoria_id, empresa_id) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssii", $nome, $email, $senha_hash, $categoria_id, $empresa_id);
        }
        
        $stmt->execute();
        $_SESSION['sucesso'] = $id > 0 ? "Usuário atualizado!" : "Usuário cadastrado!";

    } catch (mysqli_sql_exception $e) {
        $_SESSION['erro'] = "Erro ao salvar: E-mail já existe ou dados inválidos.";
    }
}

header("Location: layout.php?pagina=usuarios");
exit;