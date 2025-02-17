<?php
// templates/header.php
session_start();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Ponto</title>
    <link rel="stylesheet" href="/ponto/css/style.css">
    <style>
        /* Estilos para o cabeçalho */
        header {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #333;
            padding: 10px 20px;
        }

        header img {
            height: 50px; /* Ajuste o tamanho do logo conforme necessário */
            margin-right: 15px;
        }

        header h3 {
            color: white;
            margin: 0;
        }

        /* Estilos para o menu horizontal */
        .menu {
            background-color: #444;
            padding: 10px 0;
            text-align: center;
        }

        .menu a {
            display: inline-block;
            margin: 0 15px;
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        .menu a:hover {
            color: #ff9800;
        }
    </style>
</head>
<body>

<header>
    <img src="/ponto/templates/logo.webp" alt="Logo do Sistema">
    <h3>SISTEMA DE PONTO</h3>
</header>

<?php if (isset($_SESSION['usuario'])): ?>
    <nav class="menu">
        <a href="/ponto/user/bater_ponto.php">Bater ponto</a>
        <a href="/ponto/user/ver_meus_pontos.php">Ver Meus Pontos</a>
        <a href="/ponto/user/pedir_revisao.php">Pedir Revisão</a>
        <a href="/ponto/user/trocar_senha.php">Trocar senha</a>
        <a href="/ponto/user/logout.php">Sair</a>
<hr>
        <?php if ($_SESSION['usuario'] === 'admin'): ?>
            <a href="/ponto/admin/admin.php">Administração</a>
            <a href="/ponto/admin/listar_pontos.php">Listar Pontos</a>
            <a href="/ponto/admin/pedidos_da_equipe.php">Pedidos de Alteração</a>
            <a href="/ponto/admin/gerar_relatorio.php">Gerar Relatório</a>
            <a href="/ponto/admin/editar_manualmente.php">Editar Manualmente</a>
        <?php endif; ?>
    </nav>
<?php endif; ?>

</body>
</html>
