<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>CRUD Simples - Cadastrar Empresa</title>
</head>
<body>
    <a href="empresas.php">Voltar</a><br><br>
    <h1>Cadastrar Empresa</h1>

    <form method="POST" action="empresa_processa_cadastrar.php">
        <label>Razão Social: </label><br>
        <input type="text" name="razao_social" required><br><br>

        <label>Nome Fantasia: </label><br>
        <input type="text" name="nome_fantasia" required><br><br>

        <label>CNPJ: </label><br>
        <input type="text" name="cnpj"><br><br>

        <label>Telefone: </label><br>
        <input type="text" name="telefone"><br><br>

        <label>E-mail: </label><br>
        <input type="email" name="email"><br><br>

        <input type="submit" value="Salvar Empresa">
    </form>
</body>
</html>