<?php
session_start();
require_once '../config/db.php';

// 1. VERIFICAÇÃO DE SEGURANÇA
// Segurança: Apenas administradores podem acessar esta página
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'admin') {
    header("Location: ../pages/login.php");
    exit();
}

$counts = [
    'clientes' => 0,
    'prestadores' => 0,
    'agendamentos' => 0
];

try {
    $pdo = obterConexaoPDO();

    $stmt_clientes = $pdo->query("SELECT COUNT(*) AS total FROM Cliente");
    $counts['clientes'] = $stmt_clientes->fetch(PDO::FETCH_ASSOC)['total'];

    $stmt_prestadores = $pdo->query("SELECT COUNT(*) AS total FROM Prestador");
    $counts['prestadores'] = $stmt_prestadores->fetch(PDO::FETCH_ASSOC)['total'];

    $stmt_agendamentos = $pdo->query("SELECT COUNT(*) AS total FROM Agendamento");
    $counts['agendamentos'] = $stmt_agendamentos->fetch(PDO::FETCH_ASSOC)['total'];
} catch (PDOException $e) {
    error_log("Erro ao buscar dados do dashboard do admin: " . $e->getMessage());
}

// 2. INCLUSÃO DO LAYOUT PADRÃO
include '../includes/header.php';
include '../includes/navbar_logged_in.php';
include '../includes/sidebar.php';
?>

<div class="container-fluid">
    <h1>Painel de Controle</h1>
    <hr>

    <h3>Bem-vindo(a), <?= htmlspecialchars($_SESSION['usuario_nome']) ?>!</h3>

    <p>Este é o seu painel de controle. A partir daqui, você poderá gerir todas as funcionalidades do sistema.</p>
    <p>O seu tipo de utilizador é: <strong><?= htmlspecialchars($_SESSION['usuario_tipo']) ?></strong>.</p>

    <div class="row mt-4">
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 shadow-sm border-primary">
                <div class="card-body">
                    <h5 class="card-title text-primary">Total de Clientes</h5>
                    <h2 class="card-text display-4"><?= $counts['clientes'] ?></h2>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 shadow-sm border-success">
                <div class="card-body">
                    <h5 class="card-title text-success">Total de Prestadores</h5>
                    <h2 class="card-text display-4"><?= $counts['prestadores'] ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 shadow-sm border-info">
                <div class="card-body">
                    <h5 class="card-title text-info">Total de Agendamentos</h5>
                    <h2 class="card-text display-4"><?= $counts['agendamentos'] ?></h2>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body text-center">
                    <i class="fas fa-users fa-3x text-primary mb-3"></i>
                    <h5 class="card-title">Gerir Utilizadores</h5>
                    <p class="card-text">Gerir clientes e prestadores de serviço.</p>
                    <a href="gerir_utilizadores.php" class="btn btn-primary">Acessar</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body text-center">
                    <i class="fas fa-calendar-check fa-3x text-success mb-3"></i>
                    <h5 class="card-title">Gerir Agendamentos</h5>
                    <p class="card-text">Ver e gerir todos os agendamentos do sistema.</p>
                    <a href="gerir_agendamentos.php" class="btn btn-success">Acessar</a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body text-center">
                    <i class=" fa-3x bi bi-plus-square-fill text-info mb-3"></i>
                    <h5 class="card-title">Cadastrar Administrador</h5>
                    <p class="card-text">Adicione um novo administrador ao sistema.</p>
                    <a href="cadastraraadm.php" class="btn btn-info">
                        Cadastrar Agora
                        <span class="stretched-link"></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Inclui o rodapé da página
include '../includes/footer.php';
?>