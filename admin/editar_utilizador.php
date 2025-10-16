<?php
session_start();
require_once '../config/db.php';

// Segurança: Apenas administradores podem aceder
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'admin') {
    header("Location: ../pages/login.php");
    exit();
}

$pdo = obterConexaoPDO();

// --- 1. LÓGICA DE ATUALIZAÇÃO (QUANDO O FORMULÁRIO É ENVIADO VIA POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Validação básica dos campos recebidos
    if (isset($_POST['id'], $_POST['tipo'], $_POST['nome'], $_POST['email'])) {
        $id = $_POST['id'];
        $tipo = $_POST['tipo'];
        $nome = trim($_POST['nome']);
        $email = trim($_POST['email']);

        try {
            // Lógica segura para ATUALIZAR os dados com base no tipo
            if ($tipo === 'cliente') {
                $stmt = $pdo->prepare("UPDATE Cliente SET nome = ?, email = ? WHERE id = ?");
                $stmt->execute([$nome, $email, $id]);
            } elseif ($tipo === 'prestador') {
                $stmt = $pdo->prepare("UPDATE Prestador SET nome_razão_social = ?, email = ? WHERE id = ?");
                $stmt->execute([$nome, $email, $id]);
            } else {
                // Se o tipo for inválido, define uma mensagem de erro
                $_SESSION['mensagem_erro'] = "Tipo de utilizador inválido.";
                header("Location: gerir_utilizadores.php");
                exit();
            }

            // Se a atualização for bem-sucedida
            $_SESSION['mensagem_sucesso'] = "Utilizador atualizado com sucesso!";
            header("Location: gerir_utilizadores.php");
            exit();

        } catch (PDOException $e) {
            // Em caso de erro na base de dados (ex: e-mail duplicado)
            $_SESSION['mensagem_erro'] = "Erro ao atualizar o utilizador. Verifique se o e-mail já existe.";
            // Para depuração: error_log("Erro ao atualizar utilizador: " . $e->getMessage());
            header("Location: gerir_utilizadores.php");
            exit();
        }
    } else {
        // Se os campos obrigatórios não forem enviados
        $_SESSION['mensagem_erro'] = "Dados insuficientes para atualizar.";
        header("Location: gerir_utilizadores.php");
        exit();
    }
}


// --- 2. LÓGICA PARA BUSCAR DADOS (QUANDO A PÁGINA É CARREGADA VIA GET) ---
$utilizador = null;
if (isset($_GET['id']) && isset($_GET['tipo'])) {
    $id = $_GET['id'];
    $tipo = $_GET['tipo'];
    
    $tabela = ($tipo === 'cliente') ? 'Cliente' : (($tipo === 'prestador') ? 'Prestador' : null);

    if (!$tabela) {
        die("Tipo de utilizador inválido.");
    }

    try {
        if ($tipo === 'cliente') {
            $stmt = $pdo->prepare("SELECT id, nome, sobrenome, email, data_nascimento, telefone FROM Cliente WHERE id = ?");
            $stmt->execute([$id]);
            $utilizador = $stmt->fetch();
        } elseif ($tipo === 'prestador') {
            $stmt = $pdo->prepare("SELECT id, nome_razão_social, sobrenome_nome_fantasia, email FROM Prestador WHERE id = ?");
            $stmt->execute([$id]);
            $utilizador = $stmt->fetch();
            
            // Renomeia as chaves para corresponderem ao formulário de forma consistente
            if ($utilizador) {
                $utilizador['nome'] = $utilizador['nome_razão_social'];
                $utilizador['sobrenome'] = $utilizador['sobrenome_nome_fantasia'];
            }
        }
    } catch (PDOException $e) {
        die("Erro ao buscar dados do utilizador: " . $e->getMessage());
    }

    if (!$utilizador) {
        die("Utilizador não encontrado.");
    }
} else {
    die("Parâmetros inválidos para edição.");
}

// --- 3. HTML DA PÁGINA ---
include '../includes/header.php';
include '../includes/navbar_logged_in.php';
include '../includes/sidebar.php';
?>

<div class="container-fluid p-4">
    <h1 class="mb-4">Editar Utilizador</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title">A editar: <?= htmlspecialchars($utilizador['nome']) ?></h5>
            
            <form action="editar_utilizador.php" method="post">
                <input type="hidden" name="id" value="<?= htmlspecialchars($utilizador['id']) ?>">
                <input type="hidden" name="tipo" value="<?= htmlspecialchars($tipo) ?>">

                <div class="mb-3">
                    <label for="nome" class="form-label"><?= ($tipo === 'prestador') ? 'Nome / Razão Social:' : 'Nome:' ?></label>
                    <input type="text" class="form-control" id="nome" placeholder="Digite o nome" name="nome" value="<?= htmlspecialchars($utilizador['nome']) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" class="form-control" id="email" placeholder="Digite o email" name="email" value="<?= htmlspecialchars($utilizador['email']) ?>" required>
                </div>
                
                <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                <a href="gerir_utilizadores.php" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>