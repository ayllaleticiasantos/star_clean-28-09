<?php
session_start();
require_once '../config/db.php';

// Segurança: Apenas clientes podem acessar a esta página
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'cliente') {
    header("Location: ../pages/login.php");
    exit();
}

$id_cliente = $_SESSION['usuario_id'];
$notificacoes = [];

try {
    $pdo = obterConexaoPDO();
    $stmt = $pdo->prepare(
        "SELECT a.id, p.nome_razão_social AS nome_prestador, s.titulo AS titulo_servico, a.data, a.hora, a.status
         FROM Agendamento a
         JOIN Prestador p ON a.Prestador_id = p.id
         JOIN Servico s ON a.Servico_id = s.id
         WHERE a.Cliente_id = ? AND a.status = 'aceito'
         ORDER BY a.data DESC, a.hora DESC
         LIMIT 5"
    );
    $stmt->execute([$id_cliente]);
    $notificacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Erro ao buscar notificações do cliente: " . $e->getMessage());
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

        <?php if (!empty($notificacoes)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <h4 class="alert-heading">Novos Agendamentos Aceites!</h4>
                <p>O prestador **<?= htmlspecialchars($notificacoes[0]['nome_prestador']) ?>** aceitou o seu agendamento para **<?= htmlspecialchars($notificacoes[0]['titulo_servico']) ?>** no dia **<?= date('d/m/Y', strtotime($notificacoes[0]['data'])) ?>** às **<?= htmlspecialchars(substr($notificacoes[0]['hora'], 0, 5)) ?>**.</p>
                <hr>
                <p class="mb-0">Pode ver todos os seus agendamentos na página "Meus Agendamentos".</p>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

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
                        <a href="meus_agendamentos.php" class="btn btn-success">Ver Agendamentos</a>
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