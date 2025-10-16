<?php
// Inclui o arquivo de conexão com o banco de dados
require_once __DIR__ . '/../config/db.php';

// Verifica se o usuário está logado
if (isset($_SESSION['usuario_id'])) {
    $id_usuario = $_SESSION['usuario_id'];
    $tipo_usuario = $_SESSION['usuario_tipo'];

    $pdo = obterConexaoPDO();
    $notifications = [];
    
    try {
        if ($tipo_usuario === 'prestador') {
            $stmt = $pdo->prepare("SELECT a.data, c.nome AS nome_cliente FROM Agendamento a JOIN Cliente c ON a.Cliente_id = c.id WHERE a.Prestador_id = ? AND a.status = 'pendente' ORDER BY a.data DESC LIMIT 5");
            $stmt->execute([$id_usuario]);
            $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } elseif ($tipo_usuario === 'cliente') {
            $stmt = $pdo->prepare("SELECT a.data, p.nome_razão_social AS nome_prestador, s.titulo AS titulo_servico FROM Agendamento a JOIN Prestador p ON a.Prestador_id = p.id JOIN Servico s ON a.Servico_id = s.id WHERE a.Cliente_id = ? AND a.status = 'aceito' ORDER BY a.data DESC LIMIT 5");
            $stmt->execute([$id_usuario]);
            $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    } catch (PDOException $e) {
        error_log("Erro ao buscar notificações: " . $e->getMessage());
    }
}
?>

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container-fluid">
        
       <a class="navbar-brand" href="<?= BASE_URL ?>/index.php"><b></b>
        <i class="bi bi-star fs-3 me-2 bg-circle p-2 text-dark">StarClean</i></a>

        <div class="d-flex align-items-center">

            <div class="dropdown me-3">
                <a href="#" class="nav-link" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-bell fs-5"></i>
                    <?php if (!empty($notifications)): ?>
                        <span class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle">
                            <?= count($notifications) ?>
                        </span>
                    <?php endif; ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown">
                    <li><h6 class="dropdown-header">Notificações</h6></li>
                    <?php if (empty($notifications)): ?>
                        <li><a class="dropdown-item" href="#">
                            <small class="text-muted">Nenhuma nova notificação.</small>
                        </a></li>
                    <?php else: ?>
                        <?php foreach ($notifications as $notification): ?>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>/prestador/gerir_agendamentos.php">
                                <small><b>Novo agendamento!</b></small><br>
                                <small class="text-muted"><?= htmlspecialchars($notification['nome_cliente']) ?> agendou um serviço.</small>
                                <br>
                                <small class="text-muted"><?= date('d/m/Y', strtotime($notification['data'])) ?></small>
                            </a></li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-center" href="<?= BASE_URL ?>/prestador/gerir_agendamentos.php">Ver todas as notificações</a></li>
                </ul>
            </div>

            <div class="dropdown">
                <a href="#" class="nav-link" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user-circle fs-3"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li><h6 class="dropdown-header">Olá, <?= htmlspecialchars($_SESSION['usuario_nome'] ?? 'Convidado') ?>!</h6></li>
                    <li><a class="dropdown-item" href="<?= BASE_URL ?>/pages/perfil.php"><i class="fas fa-user-edit me-2"></i>Meu Perfil</a></li>
                    <li><a class="dropdown-item" href="<?= BASE_URL ?>/pages/configuracoes.php"><i class="fas fa-cog me-2"></i>Configurações</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="<?= BASE_URL ?>/pages/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Sair</a></li>
                </ul>
            </div>

        </div>
    </div>
</nav>