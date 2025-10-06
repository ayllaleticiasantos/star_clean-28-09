<?php
// 1. VERIFICAÇÃO DE SEGURANÇA
session_start();

// Segurança: Apenas administradores podem acessar esta página
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'admin') {
    header("Location: ../pages/login.php");
    exit();
}

// 2. INCLUSÃO DO LAYOUT PADRÃO
include '../includes/header.php';
include '../includes/navbar_logged_in.php'; // Alterado para a navbar de logado
include '../includes/sidebar.php';
?>

<div class="container-fluid">
    <h1>Painel de Controle</h1>
    <hr>

    <h3>Bem-vindo(a), <?= htmlspecialchars($_SESSION['usuario_nome']) ?>!</h3>

    <p>Este é o seu painel de controle. A partir daqui, você poderá gerir todas as funcionalidades do sistema.</p>
    <p>O seu tipo de utilizador é: <strong><?= htmlspecialchars($_SESSION['usuario_tipo']) ?></strong>.</p>

    <div class="alert alert-info mt-4">
        Próximos passos: Adicione aqui os cartões (cards), tabelas e gráficos com as funcionalidades principais do sistema.
    </div>
    <div class="row">
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body text-center">
                        <i class=" fa-3x bi bi-plus-square-fill text-primary mb-3"></i>
                        <h5 class="card-title">Cadastrar Administrador</h5>
                        <p class="card-text">Adicione um novo administrador ao sistema.</p>
                        <a href="cadastraraadm.php" class="btn btn-primary">
                            Cadastrar Agora
                            <span class="stretched-link"></span>
                        </a>
                    </div>

                </div>
</div>

<?php
// Inclui o rodapé da página
include '../includes/footer.php';
?>