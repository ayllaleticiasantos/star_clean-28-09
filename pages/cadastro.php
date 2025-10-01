<?php
session_start();
require_once '../config/db.php';

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo = $_POST['tipo'];
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    try {
        $pdo = obterConexaoPDO();

        switch ($tipo) {
            case 'cliente':
                $nome = trim($_POST['nome']);
                $sobrenome = trim($_POST['sobrenome']);
                $cpf = trim($_POST['cpf']);
                $telefone = trim($_POST['telefone']);
                $data_nascimento = $_POST['data_nascimento'];

                // Verifica e-mail/CPF duplicados
                $stmt = $pdo->prepare("SELECT id FROM Cliente WHERE email = ? OR cpf = ?");
                $stmt->execute([$email, $cpf]);

                if ($stmt->fetch()) {
                    $mensagem = '<div class="alert alert-danger">E-mail ou CPF já cadastrados!</div>';
                } else {
                    $stmt = $pdo->prepare("
                        INSERT INTO Cliente (nome, sobrenome, email, data_nascimento, telefone, cpf, password) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)
                    ");
                    $stmt->execute([$nome, $sobrenome, $email, $data_nascimento, $telefone, $cpf, $senhaHash]);
                    $_SESSION['mensagem_sucesso'] = "Cadastro de cliente realizado com sucesso!";
                    header("Location: login.php");
                    exit();
                }
                break;

            case 'prestador':
                $nomeRazao = trim($_POST['nome_razao']);
                $sobrenomeFantasia = trim($_POST['sobrenome_fantasia']);
                $cpfCnpj = trim($_POST['cpf_cnpj']);
                $telefone = trim($_POST['telefone']);
                $especialidade = trim($_POST['especialidade']);
                $descricao = trim($_POST['descricao']);
                $admin_id = 1; // por enquanto fixo; pode usar $_SESSION no futuro

                $stmt = $pdo->prepare("SELECT id FROM Prestador WHERE email = ? OR cpf_cnpj = ?");
                $stmt->execute([$email, $cpfCnpj]);

                if ($stmt->fetch()) {
                    $mensagem = '<div class="alert alert-danger">E-mail ou CPF/CNPJ já cadastrados!</div>';
                } else {
                    $stmt = $pdo->prepare("
                        INSERT INTO Prestador (nome_razão_social, sobrenome_nome_fantasia, cpf_cnpj, email, telefone, especialidade, descricao, password, Administrador_id) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
                    ");
                    $stmt->execute([$nomeRazao, $sobrenomeFantasia, $cpfCnpj, $email, $telefone, $especialidade, $descricao, $senhaHash, $admin_id]);
                    $_SESSION['mensagem_sucesso'] = "Cadastro de prestador realizado com sucesso!";
                    header("Location: login.php");
                    exit();
                }
                break;

            case 'admin':
                $nome = trim($_POST['nome']);
                $sobrenome = trim($_POST['sobrenome']);

                $stmt = $pdo->prepare("SELECT id FROM Administrador WHERE email = ?");
                $stmt->execute([$email]);

                if ($stmt->fetch()) {
                    $mensagem = '<div class="alert alert-danger">E-mail já cadastrado!</div>';
                } else {
                    $stmt = $pdo->prepare("
                        INSERT INTO Administrador (nome, sobrenome, email, password) 
                        VALUES (?, ?, ?, ?)
                    ");
                    $stmt->execute([$nome, $sobrenome, $email, $senhaHash]);
                    $_SESSION['mensagem_sucesso'] = "Cadastro de administrador realizado com sucesso!";
                    header("Location: login.php");
                    exit();
                }
                break;
        }
    } catch (Exception $e) {
        $mensagem = '<div class="alert alert-danger">Erro ao cadastrar. Tente novamente.</div>';
        // error_log($e->getMessage());
    }
}

include '../includes/header.php';
include '../includes/navbar.php';
?>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card p-4 shadow-sm" style="width: 100%; max-width: 600px;">
        <h3 class="text-center mb-4">Cadastro de Novo Usuário</h3>

        <?php if (!empty($mensagem)): ?>
            <?= $mensagem ?>
        <?php endif; ?>

        <form action="cadastro.php" method="post">
            <!-- Campos comuns -->
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
                    <label class="form-check-label" for="tipoCliente">Cliente</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="tipo" id="tipoPrestador" value="prestador">
                    <label class="form-check-label" for="tipoPrestador">Prestador</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="tipo" id="tipoAdmin" value="admin">
                    <label class="form-check-label" for="tipoAdmin">Administrador</label>
                </div>
            </div>

            <!-- Campos Cliente -->
            <div id="camposCliente" style="display: block;">
                <div class="mb-3">
                    <label for="nome" class="form-label">Nome:</label>
                    <input type="text" class="form-control" name="nome" id="nome">
                </div>
                <div class="mb-3">
                    <label for="sobrenome" class="form-label">Sobrenome:</label>
                    <input type="text" class="form-control" name="sobrenome" id="sobrenome">
                </div>
                <div class="mb-3">
                    <label for="cpf" class="form-label">CPF:</label>
                    <input type="text" class="form-control" name="cpf" id="cpf">
                </div>
                <div class="mb-3">
                    <label for="telefone" class="form-label">Telefone:</label>
                    <input type="text" class="form-control" name="telefone" id="telefone">
                </div>
                <div class="mb-3">
                    <label for="data_nascimento" class="form-label">Data de Nascimento:</label>
                    <input type="date" class="form-control" name="data_nascimento" id="data_nascimento">
                </div>
            </div>

            <!-- Campos Prestador -->
            <div id="camposPrestador" style="display: none;">
                <div class="mb-3">
                    <label for="nome_razao" class="form-label">Nome / Razão Social:</label>
                    <input type="text" class="form-control" name="nome_razao" id="nome_razao">
                </div>
                <div class="mb-3">
                    <label for="sobrenome_fantasia" class="form-label">Sobrenome / Nome Fantasia:</label>
                    <input type="text" class="form-control" name="sobrenome_fantasia" id="sobrenome_fantasia">
                </div>
                <div class="mb-3">
                    <label for="cpf_cnpj" class="form-label">CPF/CNPJ:</label>
                    <input type="text" class="form-control" name="cpf_cnpj" id="cpf_cnpj">
                </div>
                <div class="mb-3">
                    <label for="telefone" class="form-label">Telefone:</label>
                    <input type="text" class="form-control" name="telefone" id="telefone">
                </div>
                <div class="mb-3">
                    <label for="especialidade" class="form-label">Especialidade:</label>
                    <input type="text" class="form-control" name="especialidade" id="especialidade">
                </div>
                <div class="mb-3">
                    <label for="descricao" class="form-label">Descrição:</label>
                    <textarea class="form-control" name="descricao" id="descricao"></textarea>
                </div>
            </div>

            <!-- Campos Administrador -->
            <div id="camposAdmin" style="display: none;">
                <div class="mb-3">
                    <label for="nome_admin" class="form-label">Nome:</label>
                    <input type="text" class="form-control" name="nome" id="nome_admin">
                </div>
                <div class="mb-3">
                    <label for="sobrenome_admin" class="form-label">Sobrenome:</label>
                    <input type="text" class="form-control" name="sobrenome" id="sobrenome_admin">
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100">Cadastrar</button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const radios = document.querySelectorAll('input[name="tipo"]');
    const camposCliente = document.getElementById('camposCliente');
    const camposPrestador = document.getElementById('camposPrestador');
    const camposAdmin = document.getElementById('camposAdmin');

    function toggleCampos() {
        camposCliente.style.display = 'none';
        camposPrestador.style.display = 'none';
        camposAdmin.style.display = 'none';

        if (document.getElementById('tipoCliente').checked) camposCliente.style.display = 'block';
        if (document.getElementById('tipoPrestador').checked) camposPrestador.style.display = 'block';
        if (document.getElementById('tipoAdmin').checked) camposAdmin.style.display = 'block';
    }

    radios.forEach(r => r.addEventListener('change', toggleCampos));
    toggleCampos();
});
</script>

<?php include '../includes/footer.php'; ?>
