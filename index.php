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
            <p><a href="/ponto/user/bater_ponto.php">Bater ponto</a></p>
        <p><a href="/ponto/user/ver_meus_pontos.php">Ver Meus Pontos</a></p>
        <p><a href="/ponto/user/pedir_revisao.php">Pedir Revisão</a></p>
            <p><a href="trocar_senha.php">Trocar senha</a></p>
            <form action="/ponto/user/logout.php" method="POST">
                <button type="submit">Sair</button>
            </form>
            <?php if ($_SESSION['usuario'] === 'admin'): ?>
                <p><a href="admin/admin.php">Administração</a></p>




código aqui



            <?php endif; ?>
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
