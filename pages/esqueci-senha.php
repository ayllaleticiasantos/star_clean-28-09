<?php
session_start();
require_once '../config/db.php';

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    
    if (empty($email)) {
        $mensagem = '<div class="alert alert-danger">Por favor, insira o seu e-mail.</div>';
    } else {
        try {
            $pdo = obterConexaoPDO();
            $usuario_encontrado = false;
            
            // Procura o e-mail nas três tabelas de usuários
            foreach (['clientes', 'prestadores', 'administradores'] as $tabela) {
                $stmt = $pdo->prepare("SELECT id FROM $tabela WHERE email = ?");
                $stmt->execute([$email]);
                if ($stmt->fetch()) {
                    $usuario_encontrado = true;
                    break;
                }
            }

            if ($usuario_encontrado) {
                // Gera um token seguro
                $token = bin2hex(random_bytes(50));
                
                // Define o tempo de expiração (ex: 1 hora a partir de agora)
                $data_expiracao = date('Y-m-d H:i:s', strtotime('+1 hour'));

                // Salva o token no banco de dados
                $stmt = $pdo->prepare("INSERT INTO redefinicao_senha (email, token, data_expiracao) VALUES (?, ?, ?)");
                $stmt->execute([$email, $token, $data_expiracao]);

                // --- SIMULAÇÃO DO ENVIO DE E-MAIL ---
                // Em um projeto real, você usaria uma biblioteca para enviar o link por e-mail.
                // Para testes, vamos exibir o link na tela.
                $link_redefinicao = BASE_URL . "/pages/redefinir-senha.php?token=" . $token;
                
                $mensagem = '<div class="alert alert-success">Se o e-mail estiver cadastrado, um link de redefinição foi gerado.</div>';
                $mensagem .= '<div class="alert alert-info"><strong>Link para Teste:</strong><br><a href="' . $link_redefinicao . '">' . $link_redefinicao . '</a></div>';
                
            } else {
                // Mensagem genérica para não revelar se um e-mail existe ou não no sistema
                $mensagem = '<div class="alert alert-success">Se o e-mail estiver cadastrado, você receberá as instruções para redefinir sua senha.</div>';
            }

        } catch (Exception $e) {
            $mensagem = '<div class="alert alert-danger">Ocorreu um erro no sistema. Tente novamente.</div>';
            // error_log($e->getMessage());
        }
    }
}


include '../includes/header.php';
include '../includes/navbar.php';
?>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card p-4 shadow-sm" style="width: 100%; max-width: 450px;">
        <h3 class="text-center mb-4">Recuperar Senha</h3>
        <p class="text-center text-muted mb-4">Insira seu e-mail e nós enviaremos um link para você voltar a acessar à sua conta.</p>

        <?= $mensagem ?>

        <form action="esqueci-senha.php" method="post">
            <div class="mb-3">
                <label for="email" class="form-label">E-mail:</label>
                <input type="email" class="form-control" name="email" placeholder="Digite seu e-mail" id="email" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Enviar Link de Recuperação</button>
        </form>

        <div class="text-center mt-3">
            <a href="login.php">Voltar para o Login</a>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>