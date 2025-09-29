<?php
// Sempre inicie a sessão no topo dos scripts que a utilizam
session_start();

// Inclui o arquivo de conexão com o banco de dados
require_once '../config/db.php';

$mensagem_erro = '';
$mensagem_sucesso = '';

// Verifica se existe uma mensagem de sucesso vinda da página de cadastro
if (isset($_SESSION['mensagem_sucesso'])) {
    $mensagem_sucesso = '<div class="alert alert-success">' . $_SESSION['mensagem_sucesso'] . '</div>';
    // Remove a mensagem da sessão para que não seja exibida novamente
    unset($_SESSION['mensagem_sucesso']);
}

// Verifica se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    $tipo = $_POST['tipo'];

    if (empty($email) || empty($senha) || empty($tipo)) {
        $mensagem_erro = '<div class="alert alert-danger">Por favor, preencha todos os campos.</div>';
    } else {
        try {
            $pdo = obterConexaoPDO();

            // Determina a tabela correta com base no tipo de usuário
            $tabela = '';
            switch ($tipo) {
                case 'cliente':
                    $tabela = 'clientes';
                    break;
                case 'prestador':
                    $tabela = 'prestadores';
                    break;
                case 'admin':
                    $tabela = 'administradores';
                    break;
                default:
                    throw new Exception("Tipo de usuário inválido.");
            }

            // Busca o usuário pelo e-mail na tabela correta
            $stmt = $pdo->prepare("SELECT * FROM $tabela WHERE email = ?");
            $stmt->execute([$email]);
            $usuario = $stmt->fetch();

            // Verifica se o usuário foi encontrado e se a senha está correta
            if ($usuario && password_verify($senha, $usuario['senha'])) {
                // Login bem-sucedido!
                // Armazena informações do usuário na sessão
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nome'] = $usuario['nome'];
                $_SESSION['usuario_tipo'] = $tipo;

                // Redireciona para o painel apropriado (dashboard)
                switch ($tipo) {
                    case 'admin':
                        header("Location: ../admin/dashboard.php");
                        break;
                    case 'prestador':
                        header("Location: ../prestador/dashboard.php");
                        break;
                    case 'cliente':
                        // ALTERAÇÃO AQUI: Redireciona para o dashboard do cliente
                        header("Location: ../cliente/dashboard.php");
                        break;
                }
                exit();
            } else {
                // Usuário não encontrado ou senha incorreta
                $mensagem_erro = '<div class="alert alert-danger">E-mail ou senha inválidos.</div>';
            }
        } catch (Exception $e) {
            $mensagem_erro = '<div class="alert alert-danger">Ocorreu um erro no sistema. Tente novamente.</div>';
            // Em ambiente de produção, logue o erro: error_log($e->getMessage());
        }
    }
}

// Inclui os arquivos de layout
include '../includes/header.php';
include '../includes/navbar.php';
?>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card p-4 shadow-sm" style="width: 100%; max-width: 400px;">
        <h3 class="text-center mb-4">Login</h3>

        <?= $mensagem_erro ?>
        <?= $mensagem_sucesso ?>

        <form action="login.php" method="post">
            <div class="mb-3">
                <label for="email" class="form-label">E-mail:</label>
                <input type="email" class="form-control" name="email" placeholder="Digite seu e-mail" id="email" required>
            </div>

            <div class="mb-3">
                <label for="senha" class="form-label">Senha:</label>
                <input type="password" class="form-control" name="senha" placeholder="Digite sua senha" id="senha" required>
            </div>

            <div class="mb-3">
                <label for="tipo" class="form-label">Tipo de usuário:</label>
                <select name="tipo" id="tipo" class="form-select" required>
                    <option value="cliente">Cliente</option>
                    <option value="prestador">Prestador</option>
                    <option value="admin">Administrador</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary w-100">Entrar</button>
        </form>

        <div class="text-center mt-3">
            <a href="esqueci-senha.php" class="d-block">Esqueci minha senha</a>
            <span class="text-muted">Ainda não tem conta?</span>
            <a href="cadastro.php">Cadastre-se</a>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>