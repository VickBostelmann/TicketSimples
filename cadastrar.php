<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha_pura = $_POST['password']; // A senha que o usuário digitou

    // password_hash cria uma "assinatura" da senha, tornando-a ilegível no banco
    $senha_segura = password_hash($senha_pura, PASSWORD_DEFAULT);
    // -------------------------------

    try {
        // Agora salvamos a $senha_segura em vez da senha pura
        $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nome' => $nome,
            ':email' => $email,
            ':senha' => $senha_segura 
        ]);
        echo "<script>alert('Cadastro realizado com sucesso!'); window.location.href='login.php';</script>";
    } catch (PDOException $e) {
        echo "Erro ao cadastrar: E-mail já existe!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Criar Conta - TicketSimples</title>
    <style>
        body { font-family: Arial; display: flex; justify-content: center; align-items: center; height: 100vh; background: #f4f4f4; margin: 0; }
        .card { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); width: 300px; }
        h2 { text-align: center; color: #333; }
        input { display: block; width: 100%; margin: 10px 0; padding: 10px; box-sizing: border-box; border: 1px solid #ddd; border-radius: 4px; }
        button { width: 100%; padding: 10px; background: #28a745; color: white; border: none; cursor: pointer; border-radius: 4px; font-weight: bold; }
        button:hover { background: #218838; }
        .link-login { display: block; text-align: center; margin-top: 15px; font-size: 0.9em; color: #666; text-decoration: none; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Criar Conta</h2>
        <form method="POST">
            <input type="text" name="nome" placeholder="Seu Nome Completo" required>
            <input type="email" name="email" placeholder="Seu e-mail" required>
            <input type="password" name="password" placeholder="Crie uma senha" required>
            <button type="submit">Cadastrar</button>
        </form>
        <a href="login.php" class="link-login">Já tem conta? Faça Login</a>
    </div>
</body>
</html>
