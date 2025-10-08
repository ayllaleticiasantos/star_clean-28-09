<?php
session_start();
require_once '../config/db.php';

// Segurança: Apenas administradores podem aceder a esta página
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'admin') {
    header("Location: ../pages/login.php");
    exit();
}

$agendamentos = [];
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
    die("Erro ao buscar agendamentos: " . $e->getMessage());
}

include '../includes/header.php';
include '../includes/navbar_logged_in.php'; 
include '../includes/sidebar.php';
?>

<div class="container-fluid">
    <h1 class="mb-4">Gestão de Agendamentos</h1>
    <p>Visualize todos os agendamentos e o estado atual de cada serviço.</p>

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
                                        case 'pendente':
                                            $badge_class = 'bg-warning';
                                            break;
                                        case 'aceito':
                                            $badge_class = 'bg-success';
                                            break;
                                        case 'realizado':
                                            $badge_class = 'bg-primary';
                                            break;
                                        case 'cancelado':
                                            $badge_class = 'bg-danger';
                                            break;
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
</div>

<?php include '../includes/footer.php'; ?>