<?php
session_start();
require_once '../config/db.php';

// Segurança: Se não estiver logado, redireciona para o login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../pages/login.php");
    exit();
}

$id_usuario = $_SESSION['usuario_id'];
$tipo_usuario = $_SESSION['usuario_tipo'];

// Determina a tabela correta com base no tipo de usuário
$tabela = '';
switch ($tipo_usuario) {
    case 'cliente': $tabela = 'Cliente'; break;
    case 'prestador': $tabela = 'Prestador'; break;
    case 'admin': $tabela = 'Administrador'; break;
}

// Lógica para processar o formulário quando for enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo = obterConexaoPDO();

    // Se a checkbox estiver marcada, o valor é 1 (true), senão é 0 (false)
    $valor_notificacao = isset($_POST['notificacaoEmail']) ? 1 : 0;

    $stmt = $pdo->prepare("UPDATE `$tabela` SET receber_notificacoes_email = ? WHERE id = ?");
    if ($stmt->execute([$valor_notificacao, $id_usuario])) {
        $_SESSION['mensagem_sucesso'] = "Preferências salvas com sucesso!";
    } else {
        $_SESSION['mensagem_erro'] = "Erro ao salvar as preferências.";
    }
    
    // Redireciona para a mesma página para evitar reenvio
    header("Location: configuracoes.php");
    exit();
}

// Busca a preferência atual do usuário no banco de dados para exibir na página
$pdo = obterConexaoPDO();
$stmt = $pdo->prepare("SELECT receber_notificacoes_email FROM `$tabela` WHERE id = ?");
$stmt->execute([$id_usuario]);
$config_usuario = $stmt->fetch();
$prefere_email = $config_usuario['receber_notificacoes_email'] ?? 1; // Padrão é 1 (true)


include '../includes/header.php';
include '../includes/navbar_logged_in.php';
?>

<div class="container mt-5">
    <h1>Configurações</h1>
    <hr>

    <?php 
    if (isset($_SESSION['mensagem_sucesso'])) {
        echo '<div class="alert alert-success">' . $_SESSION['mensagem_sucesso'] . '</div>';
        unset($_SESSION['mensagem_sucesso']);
    }
    if (isset($_SESSION['mensagem_erro'])) {
        echo '<div class="alert alert-danger">' . $_SESSION['mensagem_erro'] . '</div>';
        unset($_SESSION['mensagem_erro']);
    }
    ?>
    
    <div class="alert alert-info">
        <p class="mb-0">Aqui você pode configurar suas preferências de notificação, privacidade e outras opções da conta.</p>
    </div>

    <form action="configuracoes.php" method="POST">
        <div class="card mt-4 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Preferências de Notificação</h5>
                
                <div class="form-check form-switch">
                    <input 
                        class="form-check-input" 
                        type="checkbox" 
                        id="notificacaoEmail" 
                        name="notificacaoEmail"
                        <?php if ($prefere_email == 1) echo 'checked'; ?>
                    >
                    <label class="form-check-label" for="notificacaoEmail">Receber notificações por e-mail</label>
                </div>
                
                <div class="form-check form-switch mt-2">
                    <input class="form-check-input" type="checkbox" id="notificacaoPush" disabled>
                    <label class="form-check-label text-muted" for="notificacaoPush">Receber notificações no app (em breve)</label>
                </div>

                <button type="submit" class="btn btn-primary mt-4">Salvar Preferências</button>
            </div>
        </div>
    </form>
</div>

<?php include '../includes/footer.php'; ?>