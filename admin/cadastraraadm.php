<?php
// cadastraadm.php

// Database connection (adjust credentials as needed)
include('../config/config.php');
include('../config/db.php');

session_start();
require_once '../config/db.php';

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo = $_POST['tipo'];
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    try {
        $pdo = obterConexaoPDO();
        $stmt = $pdo->prepare('INSERT INTO administrador (nome, sobrenome, email, password, tipo) VALUES (:nome, :sobrenome, :email, :password, :tipo)');
        $stmt->execute(['nome' => $nome, 'sobrenome' => $sobrenome, 'email' => $email, 'password' => $senhaHash, 'tipo' => $tipo]);
        $mensagem = 'Administrador cadastrado com sucesso!';
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) { // Código de erro para violação de chave única
            $mensagem = 'Erro: Email já cadastrado.';
        } else {
            $mensagem = 'Erro ao cadastrar administrador: ' . $e->getMessage();
        }
    }
}

include '../includes/header.php';
include '../includes/navbar_logged_in.php';
?>


    <title>Cadastrar Administrador</title>

    
    <?php if ($mensagem)
        echo "<p>$mensagem</p>"; ?>

    <form action="cadastraraadm.php" method="post">
        <div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
            <div class="card p-4 shadow-sm" id="camposAdmin" style="width: 100%; max-width: 600px;">
                <h2 class="text-center mb-4">Cadastrar Administrador</h2>

                <div class="mb-3">
                    <label for="nome_admin" class="form-label">Nome:</label>
                    <input type="text" class="form-control" name="nome" id="nome_admin">
                </div>
                    <div class="mb-3">
                        <label for="sobrenome_admin" class="form-label">Sobrenome:</label>
                        <input type="text" class="form-control" name="sobrenome" id="sobrenome_admin">
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail:</label>
                        <input type="email" class="form-control" name="email" id="email" required>
                    </div>

                    <div class="mb-3">
                        <label for="senha" class="form-label">Senha:</label>
                        <input type="password" class="form-control" name="senha" id="senha" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Cadastrar</button>
                </div>

               
    </form>
</body>

</html>