<?php
session_start();

$mensagemErro = "";
$mensagemSucesso = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'conexao.php';

    $nome  = trim(strip_tags($_POST['nome'] ?? ''));
    $email = trim(strip_tags($_POST['email'] ?? ''));
    $senha = $_POST['senha'] ?? '';

    $sqlVerifica = "SELECT id FROM usuarios WHERE email = ?";
    $stmtVerifica = $conexao->prepare($sqlVerifica);
    $stmtVerifica->bind_param("s", $email);
    $stmtVerifica->execute();
    $resultadoVerifica = $stmtVerifica->get_result();

    if ($resultadoVerifica->num_rows > 0) {
        $mensagemErro = "Este e-mail já está cadastrado!";
    } else {
        $senhaMd5 = md5($senha);

        $sqlInsert = "INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)";
        $stmtInsert = $conexao->prepare($sqlInsert);
        $stmtInsert->bind_param("sss", $nome, $email, $senhaMd5);

        if ($stmtInsert->execute()) {
            $mensagemSucesso = "Conta criada com sucesso! Você já pode fazer login.";
        } else {
            $mensagemErro = "Erro ao cadastrar. Tente novamente.";
        }
        $stmtInsert->close();
    }
    $stmtVerifica->close();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Criar Conta - Sistema de Credenciamento</title>
</head>
<body class="bg-light">
<div class="container mt-5" style="max-width: 400px;">
    <h2 class="text-center mb-4">Criar uma Nova Conta</h2>

    <?php if (!empty($mensagemErro)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($mensagemErro); ?></div>
    <?php endif; ?>

    <?php if (!empty($mensagemSucesso)): ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($mensagemSucesso); ?>
            <div class="mt-2"><a href="index.php" class="btn btn-sm btn-outline-success">Ir para o Login</a></div>
        </div>
    <?php endif; ?>

    <form action="cadastro.php" method="POST">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome Completo:</label>
            <input type="text" class="form-control" name="nome" id="nome" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">E-mail:</label>
            <input type="email" class="form-control" name="email" id="email" required>
        </div>
        <div class="mb-3">
            <label for="senha" class="form-label">Senha:</label>
            <input type="password" class="form-control" name="senha" id="senha" required>
        </div>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-success">Cadastrar</button>
            <a href="index.php" class="btn btn-link text-center">Já tem uma conta? Faça login</a>
        </div>
    </form>
</div>
</body>
</html>