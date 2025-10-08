<?php
session_start();
require_once '../config/db.php';

// Segurança: Apenas prestadores podem aceder
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'prestador') {
    header("Location: ../pages/login.php");
    exit();
}

// Verifica se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coleta os dados do formulário
    $titulo = trim($_POST['titulo']);
    $descricao = trim($_POST['descricao']);
    $preco = $_POST['preco'];
    $prestador_id = $_SESSION['usuario_id']; // Pega o ID do prestador logado

    // Validação simples
    if (empty($titulo) || empty($preco)) {
        $_SESSION['mensagem_erro'] = "O título e o preço são obrigatórios.";
    } else {
        try {
            $pdo = obterConexaoPDO();
            $stmt = $pdo->prepare("INSERT INTO Servico (prestador_id, titulo, descricao, preco) VALUES (?, ?, ?, ?)");
            $stmt->execute([$prestador_id, $titulo, $descricao, $preco]);

            $_SESSION['mensagem_sucesso'] = "Serviço adicionado com sucesso!";
            header("Location: gerir_servicos.php");
            exit();

        } catch (PDOException $e) {
            $_SESSION['mensagem_erro'] = "Erro ao adicionar o serviço.";
            // Para depuração: error_log($e->getMessage());
        }
    }
    // Se houver erro, redireciona de volta para o formulário para exibir a mensagem
    header("Location: adicionar_servico.php");
    exit();
}

include '../includes/header.php';
// CORREÇÃO: Usando a navbar da área logada
include '../includes/navbar_logged_in.php'; 
?>

<main class="d-flex">
    <?php include '../includes/sidebar.php'; ?>

    <div class="container-fluid p-4">
        <h1 class="mb-4">Adicionar Novo Serviço</h1>
        
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form action="adicionar_servico.php" method="post">
                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título do Serviço</label>
                        <input type="text" class="form-control" id="titulo" name="titulo" required>
                    </div>
                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição</label>
                        <textarea class="form-control" id="descricao" name="descricao" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="preco" class="form-label">Preço (R$)</label>
                        <input type="number" class="form-control" id="preco" name="preco" step="0.01" min="0" required placeholder="Ex: 50.00">
                    </div>

                    <button type="submit" class="btn btn-primary">Salvar Serviço</button>
                    <a href="gerir_servicos.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>