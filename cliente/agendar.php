<?php
session_start();
require_once '../config/db.php';

// Segurança: Apenas clientes podem aceder a esta página
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'cliente') {
    header("Location: ../pages/login.php");
    exit();
}

$mensagem = '';
$servico = null;
$endereco_cliente = null; 

if (isset($_GET['servico_id'])) {
    $servico_id = $_GET['servico_id'];
    try {
        $pdo = obterConexaoPDO();
        $stmt = $pdo->prepare(
            "SELECT s.id, s.titulo, s.descricao, s.preco, s.prestador_id, p.nome_razão_social AS nome_prestador
             FROM Servico s
             JOIN Prestador p ON s.prestador_id = p.id
             WHERE s.id = ?"
        );
        $stmt->execute([$servico_id]);
        $servico = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$servico) {
            $mensagem = '<div class="alert alert-danger">Serviço não encontrado.</div>';
        }
        
        $stmt_endereco = $pdo->prepare("SELECT id FROM Endereco WHERE Cliente_id = ? LIMIT 1");
        $stmt_endereco->execute([$_SESSION['usuario_id']]);
        $endereco_cliente = $stmt_endereco->fetch(PDO::FETCH_ASSOC);

        if (!$endereco_cliente) {
            $_SESSION['mensagem_erro'] = "Nenhum endereço encontrado. Por favor, adicione um endereço para agendar.";
            header("Location: gerir_enderecos.php");
            exit();
        }

    } catch (PDOException $e) {
        $mensagem = '<div class="alert alert-danger">Ocorreu um erro ao buscar os detalhes do serviço.</div>';
    }
} else {
    $mensagem = '<div class="alert alert-danger">ID do serviço não fornecido.</div>';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $servico && $endereco_cliente) {
    $cliente_id = $_SESSION['usuario_id'];
    $prestador_id = $servico['prestador_id'];
    $data = $_POST['data'];
    $hora = $_POST['hora'];
    $observacoes = $_POST['observacoes'];
    $status = 'pendente';
    
    $endereco_id = $endereco_cliente['id'];

    try {
        $pdo = obterConexaoPDO();
        $stmt = $pdo->prepare(
            "INSERT INTO Agendamento (Cliente_id, Prestador_id, Servico_id, Endereco_id, data, hora, status, observacoes)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([$cliente_id, $prestador_id, $servico['id'], $endereco_id, $data, $hora, $status, $observacoes]);

        $_SESSION['mensagem_sucesso'] = "Agendamento solicitado com sucesso! Aguarde a confirmação do prestador.";
        header("Location: meus_agendamentos.php");
        exit();
    } catch (PDOException $e) {
        $mensagem = '<div class="alert alert-danger">Erro ao solicitar o agendamento. ' . $e->getMessage() . '</div>';
    }
}

include '../includes/header.php';
include '../includes/navbar_logged_in.php';
?>

<main class="d-flex">
    <?php include '../includes/sidebar.php'; ?>

    <div class="container-fluid p-4">
        <h1 class="mb-4">Agendar Serviço</h1>
        <hr>

        <?= $mensagem ?>

        <?php if ($servico && $endereco_cliente): ?>
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($servico['titulo']) ?></h5>
                    <p class="card-text text-muted">Prestador: <?= htmlspecialchars($servico['nome_prestador']) ?></p>
                    <p class="card-text">Preço: <span class="text-success fw-bold">R$ <?= number_format($servico['preco'], 2, ',', '.') ?></span></p>
                    <p class="card-text"><?= htmlspecialchars($servico['descricao']) ?></p>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header">
                    <h5>Detalhes do Agendamento</h5>
                </div>
                <div class="card-body">
                    <form action="agendar.php?servico_id=<?= htmlspecialchars($servico['id']) ?>" method="post">
                        <div class="mb-3">
                            <label for="data" class="form-label">Data do Serviço:</label>
                            <input type="date" class="form-control" id="data" name="data" required>
                        </div>
                        <div class="mb-3">
                            <label for="hora" class="form-label">Hora do Serviço:</label>
                            <input type="time" class="form-control" id="hora" name="hora" required>
                        </div>
                        <div class="mb-3">
                            <label for="observacoes" class="form-label">Observações (opcional):</label>
                            <textarea class="form-control" id="observacoes" name="observacoes" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Confirmar Agendamento</button>
                        <a href="buscar_servicos.php" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include '../includes/footer.php'; ?>