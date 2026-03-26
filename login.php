<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - Sistema de Pedidos</title>
    <style>
        body { font-family: Arial; display: flex; justify-content: center; align-items: center; height: 100vh; background: #f4f4f4; }
        .login-card { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        input { display: block; width: 100%; margin: 10px 0; padding: 10px; }
        button { width: 100%; padding: 10px; background: #28a745; color: white; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <div class="login-card">
        <h2>Acessar Sistema</h2>
        <form action="testar_login.php" method="POST">
            <input type="email" name="email" placeholder="Seu E-mail" required>
            <input type="password" name="password" placeholder="Sua Senha" required>
            <button type="submit">Entrar</button>
        </form>

<p style="text-align: center; margin-top: 15px; font-size: 0.9em;">
    Novo por aqui? <a href="cadastrar.php" style="color: #007bff; text-decoration: none; font-weight: bold;">Crie sua conta</a>
</p>
    </div>
</body>
</html>