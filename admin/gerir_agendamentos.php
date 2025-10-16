<?php
session_start();
require_once '../config/db.php';

// 1. LÓGICA PHP DA PÁGINA
// Segurança: Apenas administradores podem aceder a esta página
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'admin') {
    header("Location: ../pages/login.php");
    exit();
}

$agendamentos = [];
$mensagem_erro = '';
try {
    $pdo = obterConexaoPDO();
    $stmt = $pdo->query(
        "SELECT a.id, c.nome AS nome_cliente, p.nome_razão_social AS nome_prestador, 
          s.titulo AS titulo_servico, a.data, a.hora, a.status 
         FROM Agendamento a
         JOIN Cliente c ON a.Cliente_id = c.id
         JOIN Prestador p ON a.Prestador_id = p.id
         JOIN Servico s ON a.Servico_id = s.id
         ORDER BY a.data DESC, a.hora DESC"
    );
    $agendamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Melhor prática: logar o erro e mostrar uma mensagem amigável
    error_log("Erro ao buscar agendamentos: " . $e->getMessage());
    $mensagem_erro = '<div class="alert alert-danger">Não foi possível carregar os agendamentos. Tente novamente mais tarde.</div>';
}

// 2. INCLUSÃO DO CABEÇALHO E NAVBAR
include '../includes/header.php';
include '../includes/navbar_logged_in.php';

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
                    <li class="nav-item"><a class="nav-link text-dark" href="<?= BASE_URL ?>/admin/dashboard.php"><i class="fas fa-chart-line fa-fw me-2"></i>Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link text-dark" href="<?= BASE_URL ?>/admin/gerir_utilizadores.php"><i class="fas fa-users fa-fw me-2"></i>Gerir Utilizadores</a></li>
                    <li class="nav-item"><a class="nav-link text-dark active" href="<?= BASE_URL ?>/admin/gerir_agendamentos.php"><i class="fas fa-calendar-check fa-fw me-2"></i>Gerir Agendamentos</a></li>
                <?php endif; ?>
            </ul>
        </div>

        <div class="col-12 col-md-10 p-4">
            <h1 class="mb-4">Gestão de Agendamentos</h1>
            <p>Visualize todos os agendamentos e o estado atual de cada serviço.</p>

            <?= $mensagem_erro ?>

            <div class="table-responsive mt-4">
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Prestador</th>
                            <th>Serviço</th>
                            <th>Data</th>
                            <th>Hora</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($agendamentos)): ?>
                            <tr>
                                <td colspan="7" class="text-center">Nenhum agendamento encontrado.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($agendamentos as $agendamento): ?>
                                <tr>
                                    <td><?= htmlspecialchars($agendamento['id']) ?></td>
                                    <td><?= htmlspecialchars($agendamento['nome_cliente']) ?></td>
                                    <td><?= htmlspecialchars($agendamento['nome_prestador']) ?></td>
                                    <td><?= htmlspecialchars($agendamento['titulo_servico']) ?></td>
                                    <td><?= date('d/m/Y', strtotime($agendamento['data'])) ?></td>
                                    <td><?= htmlspecialchars(substr($agendamento['hora'], 0, 5)) ?></td>
                                    <td>
                                        <?php
                                            
                                            $badge_class = 'bg-secondary';
                                            switch ($agendamento['status']) {
                                                case 'pendente':  $badge_class = 'bg-warning text-dark'; break; 
                                                case 'aceito':    $badge_class = 'bg-success'; break;
                                                case 'realizado': $badge_class = 'bg-primary'; break;
                                                case 'cancelado': $badge_class = 'bg-danger'; break;
                                            }
                                        ?>
                                        <span class="badge <?= $badge_class ?>"><?= htmlspecialchars(ucfirst($agendamento['status'])) ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div> </div> </div> <?php 
// 5. INCLUSÃO DO RODAPÉ
include '../includes/footer.php';