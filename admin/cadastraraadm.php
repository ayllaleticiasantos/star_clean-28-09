<?php
// cadastraadm.php

// Database connection (adjust credentials as needed)
include('../config/config.php');
include('../config/db.php');

session_start();
require_once '../config/db.php';

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $sobrenome = trim($_POST['sobrenome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
    $tipo = $_POST['tipo'];

    try {
        $pdo = obterConexaoPDO();
        $stmt = $pdo->prepare('INSERT INTO Administrador (nome, sobrenome, email, password, tipo) VALUES (:nome, :sobrenome, :email, :password, :tipo)');
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
include '../includes/sidebar.php';
?>

<button class="btn btn-primary d-md-none m-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu">
    <i class="fas fa-bars"></i> Menu
</button>

<div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="sidebarMenuLabel">Navegação</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0">
        <ul class="nav flex-column mt-3">
            <?php if (isset($_SESSION['usuario_tipo']) && ($_SESSION['usuario_tipo'] === 'admin' || $_SESSION['usuario_tipo'] === 'user')): ?>
                <li class="nav-item"><a class="nav-link text-dark" href="<?= BASE_URL ?>/admin/dashboard.php"><i class="fas fa-chart-line fa-fw me-2"></i>Dashboard</a></li>
                <li class="nav-item"><a class="nav-link text-dark" href="<?= BASE_URL ?>/admin/gerir_utilizadores.php"><i class="fas fa-users fa-fw me-2"></i>Gerir Utilizadores</a></li>
                <li class="nav-item"><a class="nav-link text-dark active" href="<?= BASE_URL ?>/admin/gerir_agendamentos.php"><i class="fas fa-calendar-check fa-fw me-2"></i>Gerir Agendamentos</a></li>
                 <li class="nav-item">
            <a class="nav-link text-dark" href="<?= BASE_URL ?>/admin/adicionar_servico.php"><i class="fas fa-briefcase fa-fw me-2"></i>Cadastrar Serviço</a>
        </li>
            <?php endif; ?>
        </ul>
    </div>
</div>

<div class="container-fluid">
    <div class="card p-4 shadow-sm" style="width: 100%; max-width: 600px; margin: auto;">
        <h1 class="text-center mb-4">Cadastrar Administrador</h1>
    
        <?php if ($mensagem): ?>
            <div class="alert alert-info">
                <?= htmlspecialchars($mensagem) ?>
            </div>
        <?php endif; ?>

        <form action="cadastraraadm.php" method="post">
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
            <div class="mb-3">
                <label for="tipo" class="form-label">Tipo:</label>
                <select class="form-select" name="tipo" id="tipo" required>
                    <option value="">Selecione o tipo</option>
                    <option value="adminmaster">Administrador Geral</option>
                    <option value="adminusuario">Administrador Usuário</option>
                    <option value="adminmoderador">Administrador Moderador</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary w-100">Cadastrar</button>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>