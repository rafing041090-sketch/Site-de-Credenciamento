<?php
session_start();
include_once("conexao.php");

$erro = "";

if (isset($_SESSION['usuario_id'])) {
    header("Location: layout.php?pagina=empresas");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $senha = $_POST['senha'] ?? '';

    if (!empty($email) && !empty($senha)) {
        $email_limpo = mysqli_real_escape_string($conn, trim($email));
        $senha_md5   = md5(trim($senha)); 

        $sql = "SELECT * FROM usuarios WHERE LOWER(email) = LOWER('$email_limpo') AND senha = '$senha_md5' LIMIT 1";
        $resultado = mysqli_query($conn, $sql);

        if ($resultado && mysqli_num_rows($resultado) > 0) {
            $usuario = mysqli_fetch_assoc($resultado);

            $_SESSION['usuario_id']   = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_tipo'] = $usuario['tipo'];

            header("Location: layout.php?pagina=empresas");
            exit;
        } else {
            $erro = "E-mail ou senha incorretos!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Sistema de Credenciamento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .login-card {
            max-width: 400px;
            margin: 80px auto;
            padding: 30px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

    <div class="login-card">
        <h3 class="fw-bold text-center mb-4">Sistema de Credenciamento</h3>

        <?php if (!empty($erro)): ?>
            <div class="alert alert-danger text-center p-2" role="alert">
                <?php echo $erro; ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">E-mail:</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="cliente@gmail.com" required autofocus>
            </div>

            <div class="mb-3">
                <label for="senha" class="form-label">Senha:</label>
                <input type="password" name="senha" id="senha" class="form-control" placeholder="******" required>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">Entrar</button>
        </form>
    </div>

</body>
</html>