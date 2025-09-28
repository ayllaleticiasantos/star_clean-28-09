<?php
session_start();
require_once '../config/db.php';

// Segurança: Apenas administradores podem aceder
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'admin') {
    header("Location: ../pages/login.php");
    exit();
}

// 1. VERIFICAR SE O FORMULÁRIO FOI SUBMETIDO (MÉTODO POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lógica para ATUALIZAR os dados
    $id = $_POST['id'];
    $tipo = $_POST['tipo'];
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $tabela = ($tipo === 'cliente') ? 'clientes' : 'prestadores';

    try {
        $pdo = obterConexaoPDO();
        $stmt = $pdo->prepare("UPDATE $tabela SET nome = ?, email = ? WHERE id = ?");
        $stmt->execute([$nome, $email, $id]);

        $_SESSION['mensagem_sucesso'] = "Utilizador atualizado com sucesso!";
        header("Location: gerir_utilizadores.php");
        exit();

    } catch (PDOException $e) {
        $_SESSION['mensagem_erro'] = "Erro ao atualizar o utilizador.";
        header("Location: gerir_utilizadores.php");
        exit();
    }
}

// 2. SE NÃO FOI SUBMETIDO, BUSCAR DADOS PARA EXIBIR NO FORMULÁRIO (MÉTODO GET)
$utilizador = null;
if (isset($_GET['id']) && isset($_GET['tipo'])) {
    $id = $_GET['id'];
    $tipo = $_GET['tipo'];
    $tabela = ($tipo === 'cliente') ? 'clientes' : 'prestadores';

    try {
        $pdo = obterConexaoPDO();
        $stmt = $pdo->prepare("SELECT * FROM $tabela WHERE id = ?");
        $stmt->execute([$id]);
        $utilizador = $stmt->fetch();
    } catch (PDOException $e) {
        die("Erro ao buscar dados do utilizador: " . $e->getMessage());
    }

    if (!$utilizador) {
        die("Utilizador não encontrado.");
    }
} else {
    die("Parâmetros inválidos.");
}

include '../includes/header.php';
include '../includes/navbar_logged_in.php'; // Alterado para a navbar de logado
include '../includes/sidebar.php';
?>

<div class="container-fluid">
    <h1 class="mb-4">Editar Utilizador</h1>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">A editar: <?= htmlspecialchars($utilizador['nome']) ?></h5>
            
            <form action="editar_utilizador.php" method="post">
                <input type="hidden" name="id" value="<?= htmlspecialchars($utilizador['id']) ?>">
                <input type="hidden" name="tipo" value="<?= htmlspecialchars($tipo) ?>">

                <div class="mb-3">
                    <label for="nome" class="form-label">Nome:</label>
                    <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($utilizador['nome']) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($utilizador['email']) ?>" required>
                </div>
                
                <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                <a href="gerir_utilizadores.php" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>