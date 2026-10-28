<?php
session_start();
require_once 'conexao.php';
require_once 'log_helper.php';

if (!isset($_SESSION['user_id']) && !isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}

$db = isset($conexao) ? $conexao : (isset($conn) ? $conn : null);

$id            = intval($_POST['id'] ?? 0);
$nome_fantasia = trim(strip_tags($_POST['nome_fantasia'] ?? ''));
$razao_social  = trim(strip_tags($_POST['razao_social'] ?? ''));
$cnpj          = trim(strip_tags($_POST['cnpj'] ?? ''));
$telefone      = trim(strip_tags($_POST['telefone'] ?? ''));
$email         = trim(strip_tags($_POST['email'] ?? ''));
$tipo_id       = intval($_POST['tipo_id'] ?? 0);
$qtd_espacos   = intval($_POST['quantidade_espacos'] ?? 0);
$ano           = 2026;

if (!empty($nome_fantasia) && $db) {
    if ($id > 0) {
        $stmt = $db->prepare("UPDATE empresas SET 
            nome_fantasia = ?, 
            razao_social = ?, 
            cnpj = ?, 
            telefone = ?, 
            email = ?, 
            tipo_id = ?, 
            quantidade_espacos = ?
            WHERE id = ?");
        $stmt->bind_param("sssssiii", $nome_fantasia, $razao_social, $cnpj, $telefone, $email, $tipo_id, $qtd_espacos, $id);
        $stmt->execute();

        if (function_exists('writeRegistroLog')) {
            $user_id = $_SESSION['user_id'] ?? $_SESSION['usuario_id'] ?? 1;
            writeRegistroLog("{$user_id};ALTERACAO;EMPRESAS;".date('Y-m-d H:i:s').";ID={$id},NOME_FANTASIA={$nome_fantasia}");
        }
    } else {
        $stmt = $db->prepare("INSERT INTO empresas (ano, nome_fantasia, razao_social, cnpj, telefone, email, tipo_id, quantidade_espacos) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssssii", $ano, $nome_fantasia, $razao_social, $cnpj, $telefone, $email, $tipo_id, $qtd_espacos);
        $stmt->execute();
        $novo_id = $stmt->insert_id;

        if (function_exists('writeRegistroLog')) {
            $user_id = $_SESSION['user_id'] ?? $_SESSION['usuario_id'] ?? 1;
            writeRegistroLog("{$user_id};INCLUSAO;EMPRESAS;".date('Y-m-d H:i:s').";ID={$novo_id},NOME_FANTASIA={$nome_fantasia}");
        }
    }
}

header("Location: layout.php?pagina=empresas");
exit;