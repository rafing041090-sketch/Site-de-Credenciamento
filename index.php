<?php
session_start();
require_once 'conexao.php';
$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = md5($_POST['senha'] ?? '');
    $stmt = $conexao->prepare("SELECT id, nome, categoria_id, empresa_id, senha FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($user = $result->fetch_assoc()) {
        if ($user['senha'] === $senha) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nome'] = $user['nome'];
            $_SESSION['categoria_id'] = $user['categoria_id'];
            $_SESSION['empresa_id'] = $user['empresa_id'];
            require_once 'log_helper.php';
            registrarLog('LOGIN', 'LOGIN');
            header("Location: layout.php?pagina=empresas_listar");
            exit;
        } else {
            $erro = "E-mail ou senha incorretos!";
        }
    } else {
        $erro = "E-mail ou senha incorretos!";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Credenciamento - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }
        .card-login {
            max-width: 400px;
            width: 100%;
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }
        .btn-primary {
            background-color: #0d6efd;
            border: none;
        }
    </style>
</head>
<body>

<div class="card card-login p-4">
    <div class="card-body">
        <h3 class="card-title text-center mb-4 fw-bold">Sistema de Credenciamento</h3>

        <?php if($erro): ?>
            <div class="alert alert-danger text-center p-2 mb-3" role="alert">
                <?= $erro ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">E-mail:</label>
                <input type="email" class="form-control" id="email" name="email" autofocus required>
            </div>
            
            <div class="mb-4">
                <label for="senha" class="form-label">Senha:</label>
                <input type="password" class="form-control" id="senha" name="senha" required>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary py-2 fw-semibold">Entrar</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>