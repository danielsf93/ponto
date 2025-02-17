<?php session_start(); 
// Inclui o cabeçalho
require_once __DIR__ . '/templates/header.php';

?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Inicial</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Bem-vindo, <?php echo isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'Visitante'; ?>!</h2>


      
        
        <?php if (isset($_SESSION['usuario'])): ?>
            
            <?php if ($_SESSION['usuario'] === 'admin'): ?>
                <hr>
                <p><a href="admin/admin.php">Administração</a></p>

código aqui



<?php
session_start();

// Se for necessário restringir o acesso a admin:
if (!isset($_SESSION['usuario']) || $_SESSION['usuario'] !== 'admin') {
    header("Location: /ponto/user/login.php");
    exit();
}

// Carrega o wp-load.php para acessar as constantes de conexão (DB_HOST, DB_NAME, etc.)
require_once(dirname(__FILE__) . '/../wp-load.php');

$host     = DB_HOST;
$dbname   = DB_NAME;
$username = DB_USER;
$password = DB_PASSWORD;
date_default_timezone_set('America/Sao_Paulo');

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query que retorna apenas os registros cuja última ação do dia (para cada usuário) é uma Entrada
    $sql = "
        SELECT p.usuario, p.data_hora
        FROM pontos p
        JOIN (
            SELECT usuario, MAX(data_hora) AS last_time
            FROM pontos
            WHERE DATE(data_hora) = CURDATE()
            GROUP BY usuario
        ) AS last ON p.usuario = last.usuario AND p.data_hora = last.last_time
        WHERE p.tipo = 'Entrada'
    ";
    $stmt = $pdo->query($sql);
    $entradas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}
?>

    <div class="container">
        <h2>Entradas de Hoje</h2>
        <h3>Data e Hora Atual: <?php echo date("d/m/Y H:i:s"); ?></h3>
        <?php if (count($entradas) > 0): ?>
            <table border="1" cellpadding="5" cellspacing="0">
                <tr>
                    <th>Usuário</th>
                    <th>Hora de Entrada</th>
                    <th>Tempo Decorrido</th>
                </tr>
                <?php foreach ($entradas as $entrada): 
                    // Converte a data/hora para timestamp
                    $entradaTimestamp = strtotime($entrada['data_hora']);
                    // Calcula o tempo decorrido desde a entrada até agora
                    $elapsed = time() - $entradaTimestamp;
                    $hours   = floor($elapsed / 3600);
                    $minutes = floor(($elapsed % 3600) / 60);
                    $seconds = $elapsed % 60;
                    $tempoTrabalhando = sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($entrada['usuario']); ?></td>
                    <td><?php echo date("H:i:s", $entradaTimestamp); ?></td>
                    <td><?php echo $tempoTrabalhando; ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>Nenhuma entrada registrada para hoje.</p>
        <?php endif; ?>
        <br>
        <a href="/ponto/index.php">Voltar</a>
    </div>
</body>
</html>






                <hr>
            <?php endif; ?>
            
            <p><a href="/ponto/user/bater_ponto.php">Bater ponto</a></p>
        <p><a href="/ponto/user/ver_meus_pontos.php">Ver Meus Pontos</a></p>
        <p><a href="/ponto/user/pedir_revisao.php">Pedir Revisão</a></p>
            <p><a href="trocar_senha.php">Trocar senha</a></p>
            <form action="/ponto/user/logout.php" method="POST">
                <button type="submit">Sair</button>
            </form>
            
        <?php else: ?>
            <h3>Login</h3>
            <form action="pontoec.php" method="POST">
                <input type="text" name="usuario" placeholder="Usuário" required><br>
                <input type="password" name="senha" placeholder="Senha" required><br>
                <button type="submit">Entrar</button>
            </form>
            <p><a href="/ponto/user/recuperar_senha.php">Esqueci minha senha</a></p>
        <?php endif; ?>
    </div>
</body>
</html>
