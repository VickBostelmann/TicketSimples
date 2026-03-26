<?php
// Inicia a sessão para ter acesso a ela
session_start();

// Limpa todas as variáveis da sessão (apaga o ID e o Nome do usuário da memória)
session_unset();

// Destrói a sessão completamente
session_destroy();

// Redireciona o usuário de volta para a tela de login
header("Location: login.php");
exit;