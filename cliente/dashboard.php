<?php
session_start();

// Segurança: Apenas clientes podem acessar a esta página
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'cliente') {
    header("Location: ../pages/login.php");
    exit();
}

include '../includes/header.php';
include '../includes/navbar_logged_in.php'; 
?>

<main class="d-flex">
    <?php include '../includes/sidebar.php'; ?>

    <div class="container-fluid p-4">
        <h1 class="mb-4">Painel do Cliente</h1>
        <h3>Bem-vindo(a), <?= htmlspecialchars($_SESSION['usuario_nome']) ?>!</h3>
        <hr>

        <div class="row">
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body text-center">
                        <i class="fas fa-search fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Buscar Serviços</h5>
                        <p class="card-text">Encontre os melhores prestadores para o que você precisa.</p>
                        <a href="buscar_servicos.php" class="btn btn-primary">
                            Buscar Agora
                            <span class="stretched-link"></span>
                        </a>
                    </div>

                </div>
            </div>

            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body text-center">
                        <i class="fas fa-calendar-alt fa-3x text-success mb-3"></i>
                        <h5 class="card-title">Meus Agendamentos</h5>
                        <p class="card-text">Veja o histórico e os seus próximos serviços agendados.</p>
                        <a href="#" class="btn btn-success">Ver Agendamentos</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body text-center">
                        <i class="fas fa-user-edit fa-3x text-warning mb-3"></i>
                        <h5 class="card-title">Meu Perfil</h5>
                        <p class="card-text">Mantenha seus dados de contato e de acesso atualizados.</p>
                        <a href="../pages/perfil.php" class="btn btn-warning">Editar Perfil</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>