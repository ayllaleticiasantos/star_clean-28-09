<?php
// Inicia a sessão para podermos usar variáveis de sessão para mensagens
session_start();

// Inclui o arquivo de conexão com o banco de dados
require_once '../config/db.php';

// Variáveis para armazenar mensagens de erro ou sucesso
$mensagem = '';

// Verifica se o formulário foi submetido (se o método da requisição é POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Coleta e sanitiza os dados do formulário
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha']; // A senha será tratada com password_hash
    $tipo = $_POST['tipo'];

    // 2. Validação básica
    if (empty($nome) || empty($email) || empty($senha) || empty($tipo)) {
        $mensagem = '<div class="alert alert-danger">Todos os campos são obrigatórios!</div>';
    } else {
        try {
            // Obtém a conexão PDO
            $pdo = obterConexaoPDO();

            // 3. Verifica se o e-mail já existe na tabela correspondente
            $tabela = ($tipo === 'cliente') ? 'clientes' : 'prestadores';
            $stmt = $pdo->prepare("SELECT id FROM $tabela WHERE email = ?");
            $stmt->execute([$email]);

            if ($stmt->fetch()) {
                $mensagem = '<div class="alert alert-danger">Este e-mail já está cadastrado. Tente outro.</div>';
            } else {
                // 4. Criptografa a senha com um hash seguro
                $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

                // 5. Insere os dados no banco de dados
                if ($tipo === 'cliente') {
                    $stmt = $pdo->prepare("INSERT INTO clientes (nome, email, senha) VALUES (?, ?, ?)");
                    $stmt->execute([$nome, $email, $senhaHash]);
                } else { // Se for 'prestador'
                    $especialidade = trim($_POST['especialidade']);
                    $descricao = trim($_POST['descricao']);

                    $stmt = $pdo->prepare("INSERT INTO prestadores (nome, email, senha, especialidade, descricao) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$nome, $email, $senhaHash, $especialidade, $descricao]);
                }

                // 6. Define uma mensagem de sucesso e redireciona para a página de login
                $_SESSION['mensagem_sucesso'] = "Cadastro realizado com sucesso! Faça o login para continuar.";
                header("Location: login.php");
                exit(); // Encerra o script para garantir que o redirecionamento ocorra
            }
        } catch (PDOException $e) {
            // Em um ambiente de produção, seria bom logar o erro em vez de exibi-lo
            $mensagem = '<div class="alert alert-danger">Erro ao cadastrar. Por favor, tente novamente.</div>';
            // error_log("Erro no cadastro: " . $e->getMessage());
        }
    }
}

// Inclui o cabeçalho da página
include '../includes/header.php';
include '../includes/navbar.php';
?>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card p-4 shadow-sm" style="width: 100%; max-width: 500px;">
        <h3 class="text-center mb-4">Cadastro de Novo Usuário</h3>

        <?php if (!empty($mensagem)): ?>
            <?= $mensagem ?>
        <?php endif; ?>

        <form action="cadastro.php" method="post">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome completo:</label>
                <input type="text" class="form-control" name="nome" id="nome" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">E-mail:</label>
                <input type="email" class="form-control" name="email" id="email" required>
            </div>

            <div class="mb-3">
                <label for="senha" class="form-label">Senha:</label>
                <input type="password" class="form-control" name="senha" id="senha" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Tipo de conta:</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="tipo" id="tipoCliente" value="cliente" checked>
                    <label class="form-check-label" for="tipoCliente">
                        Cliente (Quero contratar serviços)
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="tipo" id="tipoPrestador" value="prestador">
                    <label class="form-check-label" for="tipoPrestador">
                        Prestador (Quero oferecer serviços)
                    </label>
                </div>
            </div>

            <div id="camposPrestador" style="display: none;">
                <div class="mb-3">
                    <label for="especialidade" class="form-label">Especialidade:</label>
                    <input type="text" class="form-control" name="especialidade" id="especialidade" placeholder="Ex: Limpeza residencial, Passar roupas">
                </div>
                <div class="mb-3">
                    <label for="descricao" class="form-label">Breve descrição sobre você:</label>
                    <textarea class="form-control" name="descricao" id="descricao" rows="3"></textarea>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100">Cadastrar</button>
        </form>

        <div class="text-center mt-3">
            <span class="text-muted">Já tem uma conta?</span>
            <a href="login.php">Faça o login</a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tipoCliente = document.getElementById('tipoCliente');
    const tipoPrestador = document.getElementById('tipoPrestador');
    const camposPrestador = document.getElementById('camposPrestador');

    function toggleCamposPrestador() {
        if (tipoPrestador.checked) {
            camposPrestador.style.display = 'block';
        } else {
            camposPrestador.style.display = 'none';
        }
    }

    tipoCliente.addEventListener('change', toggleCamposPrestador);
    tipoPrestador.addEventListener('change', toggleCamposPrestador);
});
</script>

<?php include '../includes/footer.php'; ?>