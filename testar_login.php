<?php
require 'config.php';
session_start();

if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $senha_digitada = $_POST['password'];

    // 1. Buscamos o usuário apenas pelo e-mail
    $sql = "SELECT * FROM usuarios WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':email', $email);
    $stmt->execute();
    $usuario = $stmt->fetch();

    // 2. Verificamos se o usuário existe E se a senha bate com a criptografia
    if ($usuario && password_verify($senha_digitada, $usuario['senha'])) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        header("Location: painel.php");
    } else {
        echo "<h1>E-mail ou senha incorretos!</h1>";
        echo "<a href='login.php'>Tentar novamente</a>";
    }
}