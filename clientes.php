<?php
session_start();
require 'config.php';

// Proteção: Só entra se estiver logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

// Lógica para cadastrar novo cliente
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome_cliente'];
    $whats = $_POST['whatsapp'];

    $sql = "INSERT INTO clientes (nome_cliente, whatsapp, usuario_id) VALUES (:nome, :whats, :user_id)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':nome' => $nome, ':whats' => $whats, ':user_id' => $usuario_id]);
    
    // Recarrega a página para limpar o formulário e mostrar o novo cliente
    header("Location: clientes.php");
    exit;
}

// Busca a lista de clientes apenas deste usuário
$sql = "SELECT * FROM clientes WHERE usuario_id = :id ORDER BY nome_cliente ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $usuario_id]);
$clientes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Meus Clientes - TicketSimples</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; margin: 20px; background: #f0f2f5; color: #333; }
        .container { max-width: 800px; margin: auto; }
        .card { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); margin-bottom: 20px; }
        h2 { margin-top: 0; color: #1c1e21; }
        input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; }
        .btn-salvar { background: #28a745; color: white; border: none; padding: 12px 20px; border-radius: 6px; cursor: pointer; font-weight: bold; width: 100%; }
        .btn-voltar { text-decoration: none; color: #007bff; font-weight: bold; display: inline-block; margin-bottom: 20px; }
        table { width: 100%; background: white; border-collapse: collapse; border-radius: 10px; overflow: hidden; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #f8f9fa; color: #666; font-size: 13px; }
    </style>
</head>
<body>
    <div class="container">
        <a href="painel.php" class="btn-voltar">← Voltar para o Painel</a>

        <div class="card">
            <h2>👥 Cadastrar Novo Cliente</h2>
            <form method="POST">
                <input type="text" name="nome_cliente" placeholder="Nome Completo do Cliente" required>
                <input type="text" name="whatsapp" placeholder="WhatsApp (Ex: 5511999999999)" required>
                <button type="submit" class="btn-salvar">Salvar Cliente</button>
            </form>
        </div>

        <div class="card">
            <h2>Seus Clientes</h2>
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>WhatsApp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($clientes as $c): ?>
                    <tr>
                        <td><strong><?php echo $c['nome_cliente']; ?></strong></td>
                        <td><?php echo $c['whatsapp']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>