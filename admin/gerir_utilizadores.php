<?php
session_start();
require_once '../config/db.php';

// 1. LÓGICA PHP DA PÁGINA
// Segurança: Apenas administradores podem acessar
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'admin') {
    header("Location: ../pages/login.php");
    exit();
}

// Lógica para mensagens de feedback
$mensagem_sucesso = '';
if (isset($_SESSION['mensagem_sucesso'])) {
    $mensagem_sucesso = '<div class="alert alert-success alert-dismissible fade show" role="alert">' . $_SESSION['mensagem_sucesso'] . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    unset($_SESSION['mensagem_sucesso']);
}
$mensagem_erro = '';
if (isset($_SESSION['mensagem_erro'])) {
    $mensagem_erro = '<div class="alert alert-danger alert-dismissible fade show" role="alert">' . $_SESSION['mensagem_erro'] . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    unset($_SESSION['mensagem_erro']);
}

// --- LÓGICA DE PESQUISA ---
$termo_pesquisa = $_GET['q'] ?? '';
$clientes = [];
$prestadores = [];

try {
    $pdo = obterConexaoPDO();
    $params = [];
    
    // --- BUSCA DE CLIENTES COM FILTRO ---
    $sql_clientes = "SELECT id, nome, sobrenome, telefone, email, criado_em FROM Cliente";
    if (!empty($termo_pesquisa)) {
        $sql_clientes .= " WHERE nome LIKE ? OR sobrenome LIKE ? OR email LIKE ?";
        $params_clientes = ["%$termo_pesquisa%", "%$termo_pesquisa%", "%$termo_pesquisa%"];
    }
    $sql_clientes .= " ORDER BY nome ASC";
    $stmt_clientes = $pdo->prepare($sql_clientes);
    $stmt_clientes->execute($params_clientes ?? []);
    $clientes = $stmt_clientes->fetchAll();

    // --- BUSCA DE PRESTADORES COM FILTRO ---
    $sql_prestadores = "SELECT id, nome_razão_social, cpf_cnpj, email, especialidade FROM Prestador";
    if (!empty($termo_pesquisa)) {
        $sql_prestadores .= " WHERE nome_razão_social LIKE ? OR email LIKE ? OR especialidade LIKE ?";
        $params_prestadores = ["%$termo_pesquisa%", "%$termo_pesquisa%", "%$termo_pesquisa%"];
    }
    $sql_prestadores .= " ORDER BY nome_razão_social ASC";
    $stmt_prestadores = $pdo->prepare($sql_prestadores);
    $stmt_prestadores->execute($params_prestadores ?? []);
    $prestadores = $stmt_prestadores->fetchAll();

} catch (PDOException $e) {
    error_log("Erro ao buscar utilizadores: " . $e->getMessage());
    $mensagem_erro = '<div class="alert alert-danger">Não foi possível carregar os dados dos utilizadores. Tente novamente mais tarde.</div>';
}

// 2. INCLUSÃO DO CABEÇALHO E LAYOUT
include '../includes/header.php';
include '../includes/navbar_logged_in.php';
include '../includes/sidebar.php';
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
                 <li class="nav-item">
            <a class="nav-link text-dark" href="<?= BASE_URL ?>/admin/adicionar_servico.php"><i class="fas fa-briefcase fa-fw me-2"></i>Cadastrar Serviço</a>
        </li>
            <?php endif; ?>
        </ul>
    </div>
</div>

<!-- O CONTEÚDO DA PÁGINA COMEÇA AQUI -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Gestão de Utilizadores</h1>
</div>

<?= $mensagem_sucesso ?>
<?= $mensagem_erro ?>

<!-- FORMULÁRIO DE PESQUISA -->
<div class="card shadow-sm mb-4">
    <div class="card-list-group-item">
        <form action="gerir_utilizadores.php" method="GET" class="d-flex">
            <input class="form-control me-2" type="search" name="q" placeholder="Pesquisar por nome, email, especialidade..." value="<?= htmlspecialchars($termo_pesquisa) ?>" aria-label="Pesquisar">
            <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
            <?php if (!empty($termo_pesquisa)): ?>
                <a href="gerir_utilizadores.php" class="btn btn-outline-secondary ms-2">Limpar</a>
            <?php endif; ?>
        </form>
    </div>
</div>

<!-- TABELA DE CLIENTES -->
<h3 class="mt-5">Clientes</h3>
<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
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
                    <?php if (empty($clientes)): ?>
                        <tr><td colspan="6" class="text-center">Nenhum cliente encontrado.</td></tr>
                    <?php else: ?>
                        <?php foreach ($clientes as $cliente): ?>
                            <tr>
                                <td><?= htmlspecialchars($cliente['id']) ?></td>
                                <td><?= htmlspecialchars($cliente['nome'] . ' ' . $cliente['sobrenome']) ?></td>
                                <td><?= htmlspecialchars($cliente['email']) ?></td>
                                <td><?= htmlspecialchars($cliente['telefone']) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($cliente['criado_em'])) ?></td>
                                <td>
                                    <a href="editar_utilizador.php?id=<?= $cliente['id'] ?>&tipo=cliente" class="btn btn-warning btn-sm">Editar</a>
                                    <a href="excluir_utilizador.php?id=<?= $cliente['id'] ?>&tipo=cliente" class="btn btn-danger btn-sm" onclick="return confirm('Tem a certeza?');">Excluir</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- TABELA DE PRESTADORES -->
<h3 class="mt-5">Prestadores de Serviço</h3>
<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
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
                    <?php if (empty($prestadores)): ?>
                        <tr><td colspan="6" class="text-center">Nenhum prestador encontrado.</td></tr>
                    <?php else: ?>
                        <?php foreach ($prestadores as $prestador): ?>
                            <tr>
                                <td><?= htmlspecialchars($prestador['id']) ?></td>
                                <td><?= htmlspecialchars($prestador['nome_razão_social']) ?></td>
                                <td><?= htmlspecialchars($prestador['cpf_cnpj']) ?></td>
                                <td><?= htmlspecialchars($prestador['email']) ?></td>
                                <td><?= htmlspecialchars($prestador['especialidade']) ?></td>
                                <td>
                                    <a href="editar_utilizador.php?id=<?= $prestador['id'] ?>&tipo=prestador" class="btn btn-warning btn-sm">Editar</a>
                                    <a href="excluir_utilizador.php?id=<?= $prestador['id'] ?>&tipo=prestador" class="btn btn-danger btn-sm" onclick="return confirm('Tem a certeza?');">Excluir</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php 
// 3. INCLUSÃO DO RODAPÉ
include '../includes/footer.php'; 
?>