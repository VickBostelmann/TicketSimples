<?php
session_start();
require 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $usuario_id = $_SESSION['usuario_id'];

    // UPDATE: Muda o status no banco de dados
    $sql = "UPDATE pedidos SET status = 'Finalizado' WHERE id = :id AND usuario_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id, 'user_id' => $usuario_id]);
}

// Redireciona de volta para o painel
header("Location: painel.php");
exit;