<?php
session_start();
// Inclui o cabeçalho
require_once( dirname(__FILE__) . '/../templates/header.php' );

require_once( dirname(__FILE__) . '/../../wp-load.php' );

$host     = DB_HOST;
$dbname   = DB_NAME;
$username = DB_USER;
$password = DB_PASSWORD;
date_default_timezone_set('America/Sao_Paulo');

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (!isset($_SESSION['usuario'])) {
        header("Location: /ponto/index.php");
        exit();
    }

    $usuario = $_SESSION['usuario'];

    // Buscar todos os pontos do usuário logado, ordenados por data/hora (mais recentes primeiro)
    $stmt = $pdo->prepare("SELECT data_hora, tipo FROM pontos WHERE usuario = :usuario ORDER BY data_hora DESC");
    $stmt->bindParam(':usuario', $usuario);
    $stmt->execute();
    $pontos = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Pontos</title>
    <link rel="stylesheet" href="/ponto/css/style.css">
</head>
<body>
    <div class="container">
        <h2>Meus Pontos</h2>
        <p>Usuário: <strong><?= htmlspecialchars($_SESSION['usuario']) ?></strong></p>

        <?php if (count($pontos) > 0): ?>
            <table border="1">
                <tr>
                    <th>Data e Hora</th>
                    <th>Tipo</th>
                </tr>
                <?php foreach ($pontos as $ponto): ?>
                    <tr>
                        <td><?= date("d/m/Y - H:i:s", strtotime($ponto['data_hora'])) ?></td>
                        <td><?= htmlspecialchars($ponto['tipo']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>Nenhum registro de ponto encontrado.</p>
        <?php endif; ?>

        <br>
        <a href="/ponto/index.php">Voltar</a>
    </div>
</body>
</html>
