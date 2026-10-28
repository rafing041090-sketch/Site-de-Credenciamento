<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php"); 
    exit;
}
$pagina = $_GET['pagina'] ?? 'empresas';
$cat_id = $_SESSION['categoria_id'] ?? 1;
$user_nome = $_SESSION['usuario_nome'] ?? 'Usuário';
$mensagem_sucesso = $_SESSION['sucesso'] ?? '';
$mensagem_erro = $_SESSION['erro'] ?? '';
unset($_SESSION['sucesso'], $_SESSION['erro']);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Credenciamento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body { min-height: 100vh; background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; background-color: #ffffff; border-right: 1px solid #dee2e6; }
        .sidebar .nav-link { color: #333; font-weight: bold; margin-bottom: 5px; }
        .sidebar .nav-link.active { background-color: #0d6efd; color: #fff; border-radius: 5px; }
        .header-bar { background-color: #ffffff; border-bottom: 1px solid #dee2e6; padding: 15px 30px; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 sidebar p-3">
            <h6 class="text-uppercase fw-bold mb-4">SISTEMA DE CREDENCIAMENTO</h6>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link <?= $pagina == 'empresas' ? 'active' : '' ?>" href="layout.php?pagina=empresas">EMPRESAS</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $pagina == 'pessoas' ? 'active' : '' ?>" href="layout.php?pagina=pessoas">PESSOAS</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $pagina == 'cargos' ? 'active' : '' ?>" href="layout.php?pagina=cargos">CARGOS</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $pagina == 'tipos' ? 'active' : '' ?>" href="layout.php?pagina=tipos">TIPOS</a>
                </li>
              <?php if ($cat_id == 1): ?>
                <li class="nav-item">
                    <a class="nav-link <?= $pagina == 'usuarios' ? 'active' : '' ?>" href="layout.php?pagina=usuarios">Usuários</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $pagina == 'logs' ? 'active' : '' ?>" href="layout.php?pagina=logs">
                        <i class="bi bi-journal-text"></i> Logs do Sistema
                    </a>
                </li>
            <?php endif; ?>
            </ul>
        </div>

        <div class="col-md-10 p-0">
            <div class="header-bar d-flex justify-content-between align-items-center">
                <h4 class="m-0 text-uppercase fw-bold"><?= htmlspecialchars(strtoupper($pagina)) ?></h4>
                <div>
                    <span>Olá, <strong><?= htmlspecialchars($user_nome) ?></strong></span>
                    <a href="logout.php" class="btn btn-outline-danger btn-sm ms-3">Sair</a>
                </div>
            </div>

            <div class="p-4">
                <?php if (!empty($mensagem_sucesso)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($mensagem_sucesso) ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($mensagem_erro)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($mensagem_erro) ?>
                    </div>
                <?php endif; ?>

                <?php
                                switch ($pagina) {
                    case 'empresas':
                        include 'empresas_listar.php';
                        break;
                    case 'empresas_importar':
                        include 'empresas_importar.php';
                        break;
                    case 'empresa_novo':
                    case 'empresa_form':
                    case 'empresas_form':
                        include 'empresa_form.php';
                        break;

                   case 'pessoas':
                        include 'pessoas_listar.php';
                        break;
                   case 'pessoas_importar':
                        include 'pessoas_importar.php';
                        break;
                   case 'pessoa_novo':
                   case 'pessoa_form':
                   case 'pessoas_form':
                        include 'pessoa_form.php';
                        break;
                    case 'cargos':
                        include 'cargos_listar.php';
                        break;
                    case 'cargos_form':
                    case 'cargo_form':
                        include 'cargos_form.php';
                        break;

                    case 'tipos':
                        include 'tipos_listar.php';
                        break;
                    case 'tipos_form':
                    case 'tipo_form':
                        include 'tipos_form.php';
                        break;

                    case 'usuarios':
                        if ($cat_id == 1) {
                            include 'usuario_listar.php';
                        } else {
                            echo "<div class='alert alert-danger'>Acesso não permitido.</div>";
                        }
                        break;
                    case 'usuario_form':
                    case 'usuarios_form':
                        if ($cat_id == 1) {
                            include 'usuario_form.php';
                        } else {
                            echo "<div class='alert alert-danger'>Acesso não permitido.</div>";
                        }
                        break;

                    case 'logs':
                        if ($cat_id == 1) {
                            include 'log_listar.php';
                        } else {
                            echo "<div class='alert alert-danger'>Acesso não permitido.</div>";
                        }
                        break;

                    default:
                        include 'empresas_listar.php';
                        break;
                }
?>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>