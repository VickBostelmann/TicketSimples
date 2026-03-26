<?php
$host = 'localhost';
$db   = 'sistema_pedidos';
$user = 'root';
$pass = ''; // No XAMPP a senha padrão é vazia

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    // Configura para mostrar erros caso algo dê errado
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Conectado ao banco de dados com sucesso!";
} catch (PDOException $e) {
    die("Erro ao conectar: " . $e->getMessage());
}
?>