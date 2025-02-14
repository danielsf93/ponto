<?php
session_start();

// Inclua o ambiente do WordPress para carregar as constantes de configuração.
// Se o arquivo admin.php estiver na raiz do WordPress, você pode usar:
require_once( dirname(__FILE__) . '/../../wp-load.php' );

// Caso o arquivo esteja em um subdiretório, ajuste o caminho, por exemplo:
// require_once( dirname(__FILE__) . '/../wp-load.php' );

// Agora, use as constantes definidas no wp-config.php
$host     = DB_HOST;
$dbname   = DB_NAME;
$username = DB_USER;
$password = DB_PASSWORD;
date_default_timezone_set('America/Sao_Paulo');
try {
    // Cria a conexão PDO usando as constantes
    $pdo = new PDO("mysql:host={$host};dbname={$dbname}", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verifica se o usuário logado é o administrador
    if (!isset($_SESSION['usuario']) || $_SESSION['usuario'] !== 'admin') {
        header("Location: /ponto/index.php");
        exit();
    }

    // Criação de novo usuário
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['novo_usuario'], $_POST['nova_senha'])) {
        $novo_usuario = $_POST['novo_usuario'];
        $nova_senha   = $_POST['nova_senha'];

        $stmt = $pdo->prepare("INSERT INTO usuarios (usuario, senha, tipo) VALUES (:usuario, :senha, 'usuario')");
        $stmt->bindParam(':usuario', $novo_usuario);
        $stmt->bindParam(':senha', $nova_senha);
        $stmt->execute();

        header("Location: admin.php");
        exit();
    }

    // Busca todos os usuários
    $stmt = $pdo->prepare("SELECT usuario, tipo, recuperar_senha FROM usuarios");
    $stmt->execute();
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administração</title>
    <link rel="stylesheet" href="/ponto/css/style.css">
</head>
<body>
    <div class="container">
        <h2>Painel Administrativo</h2>

        <h3>Criar Novo Usuário</h3>
        <form method="POST">
            <input type="text" name="novo_usuario" placeholder="Usuário" required>
            <input type="password" name="nova_senha" placeholder="Senha" required>
            <button type="submit">Criar Usuário</button>
        </form>
        <a href="listar_pontos.php">Listar Pontos</a>
        <h3>Lista de Usuários</h3>
        <table>
            <tr>
                <th>Usuário</th>
                <th>Tipo</th>
                <th>Ações</th>
            </tr>
            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?= htmlspecialchars($usuario['usuario']) ?></td>
                    <td><?= htmlspecialchars($usuario['tipo']) ?></td>
                    <td>
                        <?php if ($usuario['usuario'] !== 'admin'): ?>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="excluir_usuario" value="<?= htmlspecialchars($usuario['usuario']) ?>">
                                <button type="submit" onclick="return confirm('Tem certeza que deseja excluir este usuário?')">Excluir</button>
                            </form>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="mudar_senha_usuario" value="<?= htmlspecialchars($usuario['usuario']) ?>">
                                <input type="password" name="nova_senha_usuario" placeholder="Nova senha" required>
                                <button type="submit">Alterar Senha</button>
                            </form>
                        <?php else: ?>
                            <em>Admin</em>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <br>

        <h3>Usuários que solicitaram recuperação de senha</h3>
        <table border="1">
            <tr>
                <th>Usuário</th>
                <th>Data da Solicitação</th>
            </tr>
            <?php
            $stmt = $pdo->prepare("SELECT usuario, data_solicitacao FROM usuarios WHERE recuperar_senha = 1");
            $stmt->execute();
            $recuperacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($recuperacoes as $rec): 
                // Verifica se a data_solicitacao não é NULL antes de formatar
                $dataFormatada = !empty($rec['data_solicitacao']) 
                    ? date('d/m/Y - H:i:s', strtotime($rec['data_solicitacao'])) 
                    : "Sem registro";
            ?>
                <tr>
                    <td><?= htmlspecialchars($rec['usuario']) ?></td>
                    <td><?= htmlspecialchars($dataFormatada) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <br>

        <a href="/ponto/index.php">Voltar</a>
    </div>
</body>
</html>
