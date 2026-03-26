<?php
session_start();
require 'config.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

// 1. BUSCA OS CLIENTES (Para preencher a listinha de seleção)
$stmt = $pdo->prepare("SELECT * FROM clientes WHERE usuario_id = :id ORDER BY nome_cliente ASC");
$stmt->execute(['id' => $usuario_id]);
$clientes = $stmt->fetchAll();

// 2. GRAVA O PEDIDO QUANDO CLICAR EM SALVAR
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $desc = $_POST['descricao_pedido'];
    $valor = $_POST['valor'];
    $data = $_POST['data_entrega'];
    $horario = $_POST['horario_entrega']; // Pegando a hora do formulário
    $cliente_id = $_POST['cliente_id'];

    // 1. Verifique se o SQL tem exatamente 7 campos e 7 valores (:)
    $sql = "INSERT INTO pedidos (descricao_pedido, valor, data_entrega, horario_entrega, usuario_id, cliente_id, status) 
            VALUES (:desc, :valor, :data, :horario, :user_id, :cliente_id, 'Pendente')";
    
    $stmt = $pdo->prepare($sql);

    // 2. O execute precisa bater exatamente com os nomes acima
    $stmt->execute([
        ':desc'       => $desc,
        ':valor'      => $valor,
        ':data'       => $data,
        ':horario'    => $horario,    
        ':user_id'    => $usuario_id,
        ':cliente_id' => $cliente_id
    ]);

    header("Location: painel.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Novo Pedido - TicketSimples</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f0f2f5; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .form-card { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 8px 24px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        h2 { margin-top: 0; color: #1c1e21; text-align: center; }
        label { display: block; margin-top: 15px; font-weight: bold; color: #555; font-size: 14px; }
        input, select, textarea { width: 100%; padding: 12px; margin-top: 5px; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; font-size: 15px; }
        .btn-salvar { width: 100%; background: #007bff; color: white; border: none; padding: 14px; border-radius: 6px; margin-top: 20px; font-weight: bold; cursor: pointer; font-size: 16px; }
        .btn-salvar:hover { background: #0056b3; }
        .link-voltar { display: block; text-align: center; margin-top: 15px; color: #666; text-decoration: none; font-size: 14px; }
    </style>
</head>
<body>
    <div class="form-card">
        <h2>📝 Novo Pedido</h2>
        <form method="POST">
            <label>Quem é o cliente?</label>
            <select name="cliente_id" required>
                <option value="">-- Selecione um cliente --</option>
                <?php foreach($clientes as $c): ?>
                    <option value="<?php echo $c['id']; ?>"><?php echo $c['nome_cliente']; ?></option>
                <?php endforeach; ?>
            </select>

            <label>O que ele pediu?</label>
            <textarea name="descricao_pedido" placeholder="Ex: Bolo de pote chocolate" required></textarea>

            <label>Valor da venda (R$):</label>
            <input type="number" name="valor" step="0.01" placeholder="0,00" required>

            <label>Data para entrega:</label>
            <input type="date" name="data_entrega" required>

            <label>Horário da entrega:</label>
            <input type="time" name="horario_entrega" required>

            <button type="submit" class="btn-salvar">Confirmar Pedido</button>
        </form>
        <a href="painel.php" class="link-voltar">Cancelar e voltar</a>
    </div>
</body>
</html>
