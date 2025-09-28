<?php
session_start();
require_once '../config/db.php';

// Segurança: Apenas administradores podem aceder a esta página
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'admin') {
    header("Location: ../pages/login.php");
    exit();
}

// Lógica para exibir mensagens de sucesso ou erro vindas da sessão
$mensagem_sucesso = '';
if (isset($_SESSION['mensagem_sucesso'])) {
    $mensagem_sucesso = '<div class="alert alert-success">' . $_SESSION['mensagem_sucesso'] . '</div>';
    unset($_SESSION['mensagem_sucesso']);
}

$mensagem_erro = '';
if (isset($_SESSION['mensagem_erro'])) {
    $mensagem_erro = '<div class="alert alert-danger">' . $_SESSION['mensagem_erro'] . '</div>';
    unset($_SESSION['mensagem_erro']);
}

try {
    // Busca os utilizadores no banco de dados
    $pdo = obterConexaoPDO();
    $clientes = $pdo->query("SELECT id, nome, email FROM clientes")->fetchAll();
    $prestadores = $pdo->query("SELECT id, nome, email FROM prestadores")->fetchAll();
} catch (PDOException $e) {
    die("Erro ao buscar utilizadores: " . $e->getMessage());
}

include '../includes/header.php';
include '../includes/navbar_logged_in.php'; 
include '../includes/sidebar.php';
?>

<div class="container-fluid">
    <h1 class="mb-4">Gestão de Utilizadores</h1>

    <?= $mensagem_sucesso ?>
    <?= $mensagem_erro ?>

    <h3 class="mt-5">Clientes</h3>
    <div class="table-responsive">
        <table class="table table-striped table-hover" style="table-layout: fixed; width: 100%;">
            <thead class="thead-dark">
                <tr>
                    <th style="width: 10%;">ID</th>
                    <th style="width: 35%;">Nome</th>
                    <th style="width: 35%;">Email</th>
                    <th style="width: 20%;">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clientes as $cliente): ?>
                    <tr>
                        <td><?= htmlspecialchars($cliente['id']) ?></td>
                        <td><?= htmlspecialchars($cliente['nome']) ?></td>
                        <td><?= htmlspecialchars($cliente['email']) ?></td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="editar_utilizador.php?id=<?= $cliente['id'] ?>&tipo=cliente" class="btn btn-warning btn-sm">Editar</a>
                                <a href="excluir_utilizador.php?id=<?= $cliente['id'] ?>&tipo=cliente" class="btn btn-danger btn-sm" onclick="return confirm('Tem a certeza de que deseja excluir este cliente?');">Excluir</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                 <?php if (empty($clientes)): ?>
                    <tr>
                        <td colspan="4" class="text-center">Nenhum cliente encontrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <h3 class="mt-5">Prestadores de Serviço</h3>
    <div class="table-responsive">
        <table class="table table-striped table-hover" style="table-layout: fixed; width: 100%;">
             <thead class="thead-dark">
                <tr>
                    <th style="width: 10%;">ID</th>
                    <th style="width: 35%;">Nome</th>
                    <th style="width: 35%;">Email</th>
                    <th style="width: 20%;">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($prestadores as $prestador): ?>
                    <tr>
                        <td><?= htmlspecialchars($prestador['id']) ?></td>
                        <td><?= htmlspecialchars($prestador['nome']) ?></td>
                        <td><?= htmlspecialchars($prestador['email']) ?></td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="editar_utilizador.php?id=<?= $prestador['id'] ?>&tipo=prestador" class="btn btn-warning btn-sm">Editar</a>
                                <a href="excluir_utilizador.php?id=<?= $prestador['id'] ?>&tipo=prestador" class="btn btn-danger btn-sm" onclick="return confirm('Tem a certeza de que deseja excluir este prestador?');">Excluir</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($prestadores)): ?>
                    <tr>
                        <td colspan="4" class="text-center">Nenhum prestador encontrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>