<?php
if (session_status() === PHP_SESSION_NONE) session_start();
date_default_timezone_set('America/Sao_Paulo');

if (($_SESSION['categoria_id'] ?? 0) != 1) {
    echo "<div class='alert alert-danger'>Acesso restrito.</div>";
    return;
}

$logsBaseDir = __DIR__ . '/logs';
$meses = is_dir($logsBaseDir) ? array_diff(scandir($logsBaseDir), ['.', '..']) : [];
rsort($meses);
$mesSelecionado = $_GET['mes'] ?? ($meses[0] ?? '');
$arquivosLogs = [];
if ($mesSelecionado && is_dir($logsBaseDir . '/' . $mesSelecionado)) {
    $arquivosLogs = array_diff(scandir($logsBaseDir . '/' . $mesSelecionado), ['.', '..']);
    rsort($arquivosLogs);
}
$arquivoSelecionado = $_GET['arquivo'] ?? ($arquivosLogs[0] ?? '');
$linhasLog = [];
if ($mesSelecionado && $arquivoSelecionado) {
    $caminho = $logsBaseDir . '/' . $mesSelecionado . '/' . $arquivoSelecionado;
    if (file_exists($caminho)) {
        $linhasLog = file($caminho, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $linhasLog = array_reverse($linhasLog);
    }
}
?>
<div class="container-fluid mt-2">
    <h4><i class="bi bi-journal-text"></i> Logs do Sistema</h4>
    <form method="GET" action="layout.php" class="row g-2 mb-3">
        <input type="hidden" name="pagina" value="logs">
        <div class="col-md-3">
            <select name="mes" class="form-select" onchange="this.form.submit()">
                <?php foreach ($meses as $m): ?>
                <option value="<?= $m ?>" <?= $m==$mesSelecionado?'selected':'' ?>><?= $m ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <select name="arquivo" class="form-select" onchange="this.form.submit()">
                <?php foreach ($arquivosLogs as $f): ?>
                <option value="<?= $f ?>" <?= $f==$arquivoSelecionado?'selected':'' ?>><?= $f ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0" style="font-size: 13px;">
                <thead class="table-dark">
                    <tr>
                        <th>Usuário ID</th>
                        <th>Operação</th>
                        <th>Tela</th>
                        <th>Data/Hora</th>
                        <th>Dados</th>
                    </tr>
                </thead>
                <tbody>
                <?php if($linhasLog): foreach($linhasLog as $linha):
                    $c = explode(';', $linha);
                    $raw = $c[3] ?? '';
                    $dataHora = $raw ? date('d/m/Y H:i:s', strtotime($raw)) : '';
                    $badge = $c[1]=='INCLUSAO'?'success':($c[1]=='EXCLUSAO'?'danger':($c[1]=='LOGIN'?'primary':'warning'));
                ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($c[0] ?? '') ?></strong></td>
                        <td><span class="badge bg-<?= $badge ?>"><?= htmlspecialchars($c[1] ?? '') ?></span></td>
                        <td><?= htmlspecialchars($c[2] ?? '') ?></td>
                        <td style="white-space: nowrap;"><?= $dataHora ?></td>
                        <td><small><code style="word-break: break-all;"><?= htmlspecialchars(implode(' | ', array_slice($c, 4))) ?></code></small></td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr><td colspan="5" class="text-center text-muted py-3">Nenhum log</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>