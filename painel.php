<?php
session_start();
require 'config.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$nome_user = $_SESSION['usuario_nome'];

// Somas para o resumo
$totalGeral = $pdo->prepare("SELECT SUM(valor) FROM pedidos WHERE usuario_id = ?");
$totalGeral->execute([$usuario_id]);
$vTotal = $totalGeral->fetchColumn() ?? 0;

$totalFim = $pdo->prepare("SELECT SUM(valor) FROM pedidos WHERE usuario_id = ? AND status = 'Finalizado'");
$totalFim->execute([$usuario_id]);
$vFim = $totalFim->fetchColumn() ?? 0;

// BUSCA DE PEDIDOS (Ordenado por data e hora!)
$sql = "SELECT p.*, c.nome_cliente, c.whatsapp 
        FROM pedidos p 
        LEFT JOIN clientes c ON p.cliente_id = c.id 
        WHERE p.usuario_id = :id 
        ORDER BY p.data_entrega ASC, p.horario_entrega ASC"; 
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $usuario_id]);
$pedidos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel Premium - TicketSimples</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; margin: 20px; background: #f0f2f5; }
        .header { display: flex; justify-content: space-between; align-items: center; background: white; padding: 15px 25px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .resumo-container { display: flex; gap: 20px; margin: 20px 0; }
        .card { background: white; padding: 15px; border-radius: 8px; flex: 1; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .btn-acao { padding: 8px 12px; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 13px; display: inline-block; }
        .btn-excluir { background: #ff4d4d; color: white !important; padding: 8px 12px; border-radius: 5px; text-decoration: none; font-weight: bold; font-size: 13px; display: inline-block; }
        .btn-zap { background: #25D366; color: white !important; }
        table { width: 100%; background: white; border-collapse: collapse; border-radius: 10px; overflow: hidden; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #f8f9fa; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Olá, <?php echo $nome_user; ?>! 👋</h1>
        <div>
            <a href="clientes.php" style="margin-right: 15px; text-decoration: none; font-weight: bold; color: #007bff;">👥 Meus Clientes</a>
            <a href="logout.php" style="color: red; text-decoration: none; font-weight: bold;">Sair</a>
        </div>
    </div>

    <div class="resumo-container">
        <div class="card" style="border-left: 5px solid #007bff;"><small>Vendas Totais</small><h2>R$ <?php echo number_format($vTotal, 2, ',', '.'); ?></h2></div>
        <div class="card" style="border-left: 5px solid #28a745;"><small>Lucro Realizado</small><h2 style="color: #28a745;">R$ <?php echo number_format($vFim, 2, ',', '.'); ?></h2></div>
        <div class="card" style="border-left: 5px solid #ffc107;"><small>A Receber</small><h2 style="color: #856404;">R$ <?php echo number_format($vTotal - $vFim, 2, ',', '.'); ?></h2></div>
    </div>

    <a href="novo_pedido.php" class="btn-acao" style="background: #007bff; color: white; margin-bottom: 20px;">+ Novo Pedido</a>

    <table>
        <thead>
            <tr>
                <th>Entrega</th>
                <th>Cliente</th>
                <th>Pedido</th>
                <th>Valor</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($pedidos as $p): ?>
            <tr>
                <td>
                    <?php 
                        $data = ($p['data_entrega'] && $p['data_entrega'] != '0000-00-00') ? date('d/m/Y', strtotime($p['data_entrega'])) : '---';
                        $hora = ($p['horario_entrega']) ? date('H:i', strtotime($p['horario_entrega'])) : '';
                        echo "<strong>$data</strong><br><small>$hora</small>";
                    ?>
                </td>
                <td><?php echo $p['nome_cliente'] ?? '<span style="color:gray">Sem cliente</span>'; ?></td>
                <td><?php echo $p['descricao_pedido']; ?></td>
                <td>R$ <?php echo number_format($p['valor'], 2, ',', '.'); ?></td>
                <td><strong><?php echo $p['status']; ?></strong></td>
                <td style="white-space: nowrap;">
                    <?php if($p['status'] == 'Pendente'): ?>
                        <a href="finalizar_pedido.php?id=<?php echo $p['id']; ?>" class="btn-acao" style="background: #d4edda; color: #155724;">✔ Finalizar</a>
                        
                        <?php 
                            $data_zap = date('d/m', strtotime($p['data_entrega']));
                            $hora_zap = date('H:i', strtotime($p['horario_entrega']));
                            
                            $msg = "Olá *" . $p['nome_cliente'] . "*! Confirmamos seu pedido de *" . $p['descricao_pedido'] . "* para o dia *" . $data_zap . "* às *" . $hora_zap . "*. Valor: *R$ " . number_format($p['valor'],2,',','.') . "*";
                            
                            $url = "https://api.whatsapp.com/send?phone=" . $p['whatsapp'] . "&text=" . urlencode($msg);
                        ?>
                        <a href="<?php echo $url; ?>" target="_blank" class="btn-acao btn-zap">📱 Cobrar</a>
                    <?php endif; ?>

                    <a href="excluir_pedido.php?id=<?php echo $p['id']; ?>" class="btn-excluir" onclick="return confirm('Apagar?')">🗑️</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>