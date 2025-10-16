<?php
session_start();
require_once '../config/db.php';

// 1. VERIFICAÇÃO DE SEGURANÇA
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'admin') {
    header("Location: ../pages/login.php");
    exit();
}

// 2. LÓGICA DO DASHBOARD (BUSCA DE DADOS)
$counts = [
    'clientes' => 0,
    'prestadores' => 0,
    'agendamentos' => 0
];
try {
    $pdo = obterConexaoPDO();
    // Consulta otimizada para buscar todos os dados de uma vez
    $stmt = $pdo->query("
        SELECT 
            (SELECT COUNT(*) FROM Cliente) AS total_clientes,
            (SELECT COUNT(*) FROM Prestador) AS total_prestadores,
            (SELECT COUNT(*) FROM Agendamento) AS total_agendamentos
    ");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $counts['clientes'] = $result['total_clientes'];
    $counts['prestadores'] = $result['total_prestadores'];
    $counts['agendamentos'] = $result['total_agendamentos'];
} catch (PDOException $e) {
    error_log("Erro ao buscar dados do dashboard do admin: " . $e->getMessage());
}

// 3. INCLUSÃO DO CABEÇALHO E NAVBAR
include '../includes/header.php';
include '../includes/navbar_logged_in.php';

// =========================================================================
// 4. ESTRUTURA DA SIDEBAR RESPONSIVA COMEÇA AQUI
//    (Este código substitui o 'include ../includes/sidebar.php;')
// =========================================================================
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
                <li class="nav-item"><a class="nav-link text-dark active" href="<?= BASE_URL ?>/admin/dashboard.php"><i class="fas fa-chart-line fa-fw me-2"></i>Dashboard</a></li>
                <li class="nav-item"><a class="nav-link text-dark" href="<?= BASE_URL ?>/admin/gerir_utilizadores.php"><i class="fas fa-users fa-fw me-2"></i>Gerir Utilizadores</a></li>
                <li class="nav-item"><a class="nav-link text-dark" href="<?= BASE_URL ?>/admin/gerir_agendamentos.php"><i class="fas fa-calendar-check fa-fw me-2"></i>Gerir Agendamentos</a></li>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] === 'prestador'): ?>
                <?php endif; ?>

            <?php if (isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] === 'cliente'): ?>
                <?php endif; ?>
        </ul>
    </div>
</div>
<?
include '../includes/menu.php';
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 bg-white p-3 d-none d-md-block" style="min-height: 100vh;">
            <ul class="nav flex-column mt-3">
                <?php if (isset($_SESSION['usuario_tipo']) && ($_SESSION['usuario_tipo'] === 'admin' || $_SESSION['usuario_tipo'] === 'user')): ?>
                    <li class="nav-item"><a class="nav-link text-dark active" href="<?= BASE_URL ?>/admin/dashboard.php"><i class="fas fa-chart-line fa-fw me-2"></i>Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link text-dark" href="<?= BASE_URL ?>/admin/gerir_utilizadores.php"><i class="fas fa-users fa-fw me-2"></i>Gerir Utilizadores</a></li>
                    <li class="nav-item"><a class="nav-link text-dark" href="<?= BASE_URL ?>/admin/gerir_agendamentos.php"><i class="fas fa-calendar-check fa-fw me-2"></i>Gerir Agendamentos</a></li>
                <?php endif; ?>

                <?php if (isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] === 'prestador'): ?>
                    <?php endif; ?>

                <?php if (isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] === 'cliente'): ?>
                    <?php endif; ?>
            </ul>
        </div>

        <div class="col-12 col-md-10 p-4">
            <h1>Painel de Controle</h1>
            <hr>
            <h3>Bem-vindo(a), <?= htmlspecialchars($_SESSION['usuario_nome']) ?>!</h3>
            <p>Este é o seu painel de controle. A partir daqui, você poderá gerir todas as funcionalidades do sistema.</p>
            <p>O seu tipo de utilizador é: <strong><?= htmlspecialchars($_SESSION['usuario_tipo']) ?></strong>.</p>

            <div class="row mt-4">
                <div class="col-12 col-sm-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm border-primary"><div class="card-body"><h5 class="card-title text-primary">Total de Clientes</h5><h2 class="card-text display-4"><?= $counts['clientes'] ?></h2></div></div>
                </div>
                <div class="col-12 col-sm-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm border-success"><div class="card-body"><h5 class="card-title text-success">Total de Prestadores</h5><h2 class="card-text display-4"><?= $counts['prestadores'] ?></h2></div></div>
                </div>
                <div class="col-12 col-sm-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm border-info"><div class="card-body"><h5 class="card-title text-info">Total de Agendamentos</h5><h2 class="card-text display-4"><?= $counts['agendamentos'] ?></h2></div></div>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-12 col-sm-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm border-0"><div class="card-body text-center"><i class="fas fa-users fa-3x text-primary mb-3"></i><h5 class="card-title">Gerir Utilizadores</h5><p class="card-text">Gerir clientes e prestadores de serviço.</p><a href="gerir_utilizadores.php" class="btn btn-primary">Acessar</a></div></div>
                </div>
                <div class="col-12 col-sm-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm border-0"><div class="card-body text-center"><i class="fas fa-calendar-check fa-3x text-success mb-3"></i><h5 class="card-title">Gerir Agendamentos</h5><p class="card-text">Ver e gerir todos os agendamentos.</p><a href="gerir_agendamentos.php" class="btn btn-success">Acessar</a></div></div>
                </div>
                <div class="col-12 col-sm-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm border-0"><div class="card-body text-center"><i class=" fa-3x bi bi-plus-square-fill text-info mb-3"></i><h5 class="card-title">Cadastrar Administrador</h5><p class="card-text">Adicione um novo administrador.</p><a href="cadastraraadm.php" class="btn btn-info">Cadastrar Agora</a></div></div>
                </div>
            </div>
        </div> </div> </div> <?php
// 6. INCLUSÃO DO RODAPÉ
include '../includes/footer.php';
?>