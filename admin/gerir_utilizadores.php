<?php
session_start();
require_once '../config/db.php';

// 1. LÓGICA PHP DA PÁGINA
// Segurança: Apenas administradores podem aceder a esta página
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'admin') {
    header("Location: ../pages/login.php");
    exit();
}

// Lógica para exibir mensagens de feedback
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

// Lógica para buscar os dados dos utilizadores
try {
    $pdo = obterConexaoPDO();
    $clientes = $pdo->query("SELECT id, nome, sobrenome, data_nascimento, telefone, email, criado_em, atualizado_em FROM Cliente ORDER BY nome ASC")->fetchAll();
    $prestadores = $pdo->query("SELECT id, nome_razão_social, sobrenome_nome_fantasia, cpf_cnpj, email, telefone, especialidade, criado_em, atualizado_em FROM Prestador ORDER BY nome_razão_social ASC")->fetchAll();
} catch (PDOException $e) {
    // Em um ambiente de produção, seria melhor logar o erro do que usar die()
    error_log("Erro ao buscar utilizadores: " . $e->getMessage());
    $mensagem_erro = '<div class="alert alert-danger">Não foi possível carregar os dados dos utilizadores. Tente novamente mais tarde.</div>';
    $clientes = [];
    $prestadores = [];
}

// 2. INCLUSÃO DO CABEÇALHO E NAVBAR
include '../includes/header.php';
include '../includes/navbar_logged_in.php';

// =========================================================================
// 3. ESTRUTURA DA SIDEBAR RESPONSIVA (SUBSTITUI O ANTIGO INCLUDE)
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
                <li class="nav-item"><a class="nav-link text-dark" href="<?= BASE_URL ?>/admin/dashboard.php"><i class="fas fa-chart-line fa-fw me-2"></i>Dashboard</a></li>
                <li class="nav-item"><a class="nav-link text-dark active" href="<?= BASE_URL ?>/admin/gerir_utilizadores.php"><i class="fas fa-users fa-fw me-2"></i>Gerir Utilizadores</a></li>
                <li class="nav-item"><a class="nav-link text-dark" href="<?= BASE_URL ?>/admin/gerir_agendamentos.php"><i class="fas fa-calendar-check fa-fw me-2"></i>Gerir Agendamentos</a></li>
            <?php endif; ?>
        </ul>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 bg-white p-3 d-none d-md-block" style="min-height: 100vh;">
            <ul class="nav flex-column mt-3">
                <?php if (isset($_SESSION['usuario_tipo']) && ($_SESSION['usuario_tipo'] === 'admin' || $_SESSION['usuario_tipo'] === 'user')): ?>
                    <li class="nav-item"><a class="nav-link text-dark" href="<?= BASE_URL ?>/admin/dashboard.php"><i class="fas fa-chart-line fa-fw me-2"></i>Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link text-dark active" href="<?= BASE_URL ?>/admin/gerir_utilizadores.php"><i class="fas fa-users fa-fw me-2"></i>Gerir Utilizadores</a></li>
                    <li class="nav-item"><a class="nav-link text-dark" href="<?= BASE_URL ?>/admin/gerir_agendamentos.php"><i class="fas fa-calendar-check fa-fw me-2"></i>Gerir Agendamentos</a></li>
                <?php endif; ?>
            </ul>
        </div>

        <div class="col-12 col-md-10 p-4">
            <h1 class="mb-4">Gestão de Utilizadores</h1>

            <?= $mensagem_sucesso ?>
            <?= $mensagem_erro ?>

            <h3 class="mt-5">Clientes</h3>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nome Completo</th>
                            <th>Email</th>
                            <th>Telefone</th>
                            <th>Data de Criação</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clientes as $cliente): ?>
                            <tr>
                                <td><?= htmlspecialchars($cliente['id']) ?></td>
                                <td><?= htmlspecialchars($cliente['nome'] . ' ' . $cliente['sobrenome']) ?></td>
                                <td><?= htmlspecialchars($cliente['email']) ?></td>
                                <td><?= htmlspecialchars($cliente['telefone']) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($cliente['criado_em'])) ?></td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="editar_utilizador.php?id=<?= $cliente['id'] ?>&tipo=cliente" class="btn btn-warning btn-sm">Editar</a>
                                        <a href="excluir_utilizador.php?id=<?= $cliente['id'] ?>&tipo=cliente" class="btn btn-danger btn-sm" onclick="return confirm('Tem a certeza de que deseja excluir este cliente?');">Excluir</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($clientes)): ?>
                            <tr><td colspan="6" class="text-center">Nenhum cliente encontrado.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <h3 class="mt-5">Prestadores de Serviço</h3>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Razão Social</th>
                            <th>CPF/CNPJ</th>
                            <th>Email</th>
                            <th>Especialidade</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($prestadores as $prestador): ?>
                            <tr>
                                <td><?= htmlspecialchars($prestador['id']) ?></td>
                                <td><?= htmlspecialchars($prestador['nome_razão_social']) ?></td>
                                <td><?= htmlspecialchars($prestador['cpf_cnpj']) ?></td>
                                <td><?= htmlspecialchars($prestador['email']) ?></td>
                                <td><?= htmlspecialchars($prestador['especialidade']) ?></td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="editar_utilizador.php?id=<?= $prestador['id'] ?>&tipo=prestador" class="btn btn-warning btn-sm">Editar</a>
                                        <a href="excluir_utilizador.php?id=<?= $prestador['id'] ?>&tipo=prestador" class="btn btn-danger btn-sm" onclick="return confirm('Tem a certeza de que deseja excluir este prestador?');">Excluir</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($prestadores)): ?>
                            <tr><td colspan="6" class="text-center">Nenhum prestador encontrado.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div> </div> </div> <?php 
// 5. INCLUSÃO DO RODAPÉ
include '../includes/footer.php'; 
?>