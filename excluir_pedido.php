<?php
session_start();
require 'config.php';

// Verifica se o ID foi passado na URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $usuario_id = $_SESSION['usuario_id'];

    // DELETE: Remove o registro do banco de dados
    $sql = "DELETE FROM pedidos WHERE id = :id AND usuario_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id, 'user_id' => $usuario_id]);
}

// Redireciona de volta para o painel para ver a lista atualizada
header("Location: painel.php");
exit;