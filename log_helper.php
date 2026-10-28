<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
date_default_timezone_set('America/Sao_Paulo');

function registrarLog($operacao, $tela, $dadosAntigos = '', $dadosNovos = '') {
    $usuarioId = $_SESSION['user_id'] ?? $_SESSION['usuario_id'] ?? 0;
    $dataHora  = date('Y-m-d H:i:s');

    $logDir = __DIR__ . '/logs/' . date('Y-m');
    if (!file_exists($logDir)) {
        @mkdir($logDir, 0755, true);
    }

    $filePath = $logDir . '/' . date('d') . '.log';

    // ITEM 6 - garante que grava TODOS os dados
    if (is_array($dadosNovos)) {
        $dadosNovos = formatarDadosLog($dadosNovos);
    }
    if (is_array($dadosAntigos)) {
        $dadosAntigos = formatarDadosLog($dadosAntigos);
    }

    if ($operacao === 'LOGIN') {
        $linha = "{$usuarioId};{$operacao};{$tela};{$dataHora}";
    } elseif ($operacao === 'ALTERACAO') {
        $linha = "{$usuarioId};{$operacao};{$tela};{$dataHora};{$dadosAntigos};{$dadosNovos}";
    } else {
        $linha = "{$usuarioId};{$operacao};{$tela};{$dataHora};{$dadosNovos}";
    }

    file_put_contents($filePath, $linha . PHP_EOL, FILE_APPEND | LOCK_EX);
}

function formatarDadosLog($array) {
    if (!$array || !is_array($array)) return strval($array);
    $partes = [];
    foreach ($array as $chave => $valor) {
        if(in_array(strtolower($chave), ['senha','password','foto','file'])) continue;
        if(is_array($valor)) $valor = json_encode($valor);
        $valor = str_replace([';','='], [',',''], $valor);
        $partes[] = "{$chave}={$valor}";
    }
    return implode(',', $partes);
}

if (!function_exists('writeRegistroLog')) {
    function writeRegistroLog($mensagem) {
        $partes = explode(';', $mensagem);
        $operacao = $partes[1] ?? 'ACAO';
        $tela     = $partes[2] ?? 'SISTEMA';
        $detalhes = $partes[4] ?? '';
        registrarLog($operacao, $tela, '', $detalhes);
    }
}