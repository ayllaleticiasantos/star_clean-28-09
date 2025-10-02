<?php
// cadastraadm.php

// Database connection (adjust credentials as needed)
include('../config/config.php');
include('../config/db.php'); 


if ($conn->connect_error) {
    die('Erro de conexão: ' . $conn->connect_error);
}

$nome = $email = $senha = $msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $sobrenome = trim($_POST['sobrenome']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if ($nome && $email && $password) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO administrador (nome, sobrenome, email, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nome, $sobrenome, $email, $password_hash);

        $stmt->execute();
        if ($stmt->execute()) {
            $msg = "Administrador cadastrado com sucesso!";
        } else {
            $msg = "Erro ao cadastrar: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $msg = "Preencha todos os campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Administrador</title>
</head>
<body>
    <h2>Cadastrar Administrador</h2>
    <?php if ($msg) echo "<p>$msg</p>"; ?>
    <form method="post">
        <label>Nome:<br>
            <input type="text" name="nome" required>
        </label><br><br>
        <label>Sobrenome:<br>
            <input type="text" name="sobrenome" required>
        </label><br><br>
        <label>Email:<br>
            <input type="email" name="email" required>
        </label><br><br>
        <label>Senha:<br>
            <input type="password" name="password" required>
        </label><br><br>
        <button type="submit">Cadastrar</button>
    </form>
</body>
</html>