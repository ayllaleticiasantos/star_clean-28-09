<?php
session_start();
require_once '../config/db.php';

$mensagem = '';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Campos comuns que sempre virão do formulário
    $tipo = $_POST['tipo'] ?? ''; // Usar ?? para evitar erro se não vier
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    // Validação básica para campos comuns
    if (empty($tipo) || empty($email) || empty($senha)) {
        $mensagem = '<div class="alert alert-danger">Tipo, E-mail e Senha são obrigatórios!</div>';
    } else {
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
        
        try {
            $pdo = obterConexaoPDO();

            switch ($tipo) {
                // --- CASO CLIENTE ---
                case 'cliente':
                    $nome = trim($_POST['cliente_nome']);
                    $sobrenome = trim($_POST['cliente_sobrenome']);
                    $data_nascimento = $_POST['data_nascimento'];
                    $telefone = trim($_POST['cliente_telefone']);
                    $cpf = trim($_POST['cpf']);

                    // Validação de campos específicos do cliente
                    if (empty($nome) || empty($sobrenome) || empty($data_nascimento) || empty($cpf)) {
                        $mensagem = '<div class="alert alert-danger">Todos os campos do cliente são obrigatórios!</div>';
                        break; // Sai do switch
                    }

                    // Verifica e-mail/CPF duplicados
                    $stmt = $pdo->prepare("SELECT id FROM Cliente WHERE email = ? OR cpf = ?");
                    $stmt->execute([$email, $cpf]);

                    if ($stmt->fetch()) {
                        $mensagem = '<div class="alert alert-danger">E-mail ou CPF já cadastrados!</div>';
                    } else {
                        $stmt = $pdo->prepare(
                            "INSERT INTO Cliente (nome, sobrenome, email, data_nascimento, telefone, cpf, password) 
                             VALUES (?, ?, ?, ?, ?, ?, ?)"
                        );
                        $stmt->execute([$nome, $sobrenome, $email, $data_nascimento, $telefone, $cpf, $senhaHash]);
                        $_SESSION['mensagem_sucesso'] = "Cliente cadastrado com sucesso! Faça o login.";
                        header("Location: login.php");
                        exit();
                    }
                    break;

                // --- CASO PRESTADOR ---
                case 'prestador':
                    $nomeRazao = trim($_POST['prestador_nome_razao']);
                    $sobrenomeFantasia = trim($_POST['prestador_sobrenome_fantasia']);
                    $cpfCnpj = trim($_POST['cpf_cnpj']);
                    $telefone = trim($_POST['prestador_telefone']);
                    $especialidade = trim($_POST['especialidade']);
                    $descricao = trim($_POST['descricao']);

                    // Validação de campos específicos do prestador
                    if (empty($nomeRazao) || empty($cpfCnpj) || empty($especialidade)) {
                        $mensagem = '<div class="alert alert-danger">Nome/Razão Social, CPF/CNPJ e Especialidade são obrigatórios!</div>';
                        break; // Sai do switch
                    }

                    $stmt = $pdo->prepare("SELECT id FROM Prestador WHERE email = ? OR cpf_cnpj = ?");
                    $stmt->execute([$email, $cpfCnpj]);

                    if ($stmt->fetch()) {
                        $mensagem = '<div class="alert alert-danger">E-mail ou CPF/CNPJ já cadastrados!</div>';
                    } else {
                        // Lembre-se de ajustar esta lógica para definir o admin responsável
                        $admin_id_responsavel = 1; 

                        $stmt = $pdo->prepare(
                            "INSERT INTO Prestador (nome_razão_social, sobrenome_nome_fantasia, cpf_cnpj, email, telefone, especialidade, descricao, password, Administrador_id) 
                             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
                        );
                        $stmt->execute([$nomeRazao, $sobrenomeFantasia, $cpfCnpj, $email, $telefone, $especialidade, $descricao, $senhaHash, $admin_id_responsavel]);
                        $_SESSION['mensagem_sucesso'] = "Prestador cadastrado com sucesso! Faça o login.";
                        header("Location: login.php");
                        exit();
                    }
                    break;
                
                default:
                    $mensagem = '<div class="alert alert-danger">Tipo de usuário inválido!</div>';
                    break;
            }
        } catch (PDOException $e) {
            $mensagem = '<div class="alert alert-danger">Ocorreu um erro no sistema. Tente novamente.</div>';
            error_log('Erro no cadastro: ' . $e->getMessage());
        }
    }
}
include '../includes/header.php';
include '../includes/navbar.php';
?>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card p-4 shadow-sm" style="width: 100%; max-width: 600px;">
        <h3 class="text-center mb-4">Cadastro de Novo Usuário</h3>

        <?php if (!empty($mensagem)) { echo $mensagem; } ?>

        <form action="cadastro.php" method="post">
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
                <div class="form-check"><input class="form-check-input" type="radio" name="tipo" id="tipoCliente" value="cliente" checked><label class="form-check-label" for="tipoCliente">Cliente</label></div>
                <div class="form-check"><input class="form-check-input" type="radio" name="tipo" id="tipoPrestador" value="prestador"><label class="form-check-label" for="tipoPrestador">Prestador</label></div>
                </div>

            <div id="camposCliente">
                <div class="mb-3"><label for="cliente_nome" class="form-label">Nome:</label><input type="text" class="form-control" name="cliente_nome" id="cliente_nome"></div>
                <div class="mb-3"><label for="cliente_sobrenome" class="form-label">Sobrenome:</label><input type="text" class="form-control" name="cliente_sobrenome" id="cliente_sobrenome"></div>
                <div class="mb-3"><label for="cpf" class="form-label">CPF:</label><input type="text" class="form-control" name="cpf" id="cpf"></div>
                <div class="mb-3"><label for="cliente_telefone" class="form-label">Telefone:</label><input type="text" class="form-control" name="cliente_telefone" id="cliente_telefone"></div>
                <div class="mb-3"><label for="data_nascimento" class="form-label">Data de Nascimento:</label><input type="date" class="form-control" name="data_nascimento" id="data_nascimento"></div>
            </div>

            <div id="camposPrestador" style="display: none;">
                <div class="mb-3"><label for="prestador_nome_razao" class="form-label">Nome / Razão Social:</label><input type="text" class="form-control" name="prestador_nome_razao" id="prestador_nome_razao"></div>
                <div class="mb-3"><label for="prestador_sobrenome_fantasia" class="form-label">Sobrenome / Nome Fantasia:</label><input type="text" class="form-control" name="prestador_sobrenome_fantasia" id="prestador_sobrenome_fantasia"></div>
                <div class="mb-3"><label for="cpf_cnpj" class="form-label">CPF/CNPJ:</label><input type="text" class="form-control" name="cpf_cnpj" id="cpf_cnpj"></div>
                <div class="mb-3"><label for="prestador_telefone" class="form-label">Telefone:</label><input type="text" class="form-control" name="prestador_telefone" id="prestador_telefone"></div>
                <div class="mb-3"><label for="especialidade" class="form-label">Especialidade:</label><input type="text" class="form-control" name="especialidade" id="especialidade"></div>
                <div class="mb-3"><label for="descricao" class="form-label">Descrição:</label><textarea class="form-control" name="descricao" id="descricao"></textarea></div>
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
    // REFERÊNCIA AOS CAMPOS ADMIN REMOVIDA

    function toggleCampos() {
        const tipoSelecionado = document.querySelector('input[name="tipo"]:checked').value;
        
        camposCliente.style.display = tipoSelecionado === 'cliente' ? 'block' : 'none';
        camposPrestador.style.display = tipoSelecionado === 'prestador' ? 'block' : 'none';
        // LÓGICA PARA MOSTRAR/ESCONDER CAMPOS ADMIN REMOVIDA
    }

    radios.forEach(radio => radio.addEventListener('change', toggleCampos));
    
    toggleCampos(); 
});
</script>
<?php
// Resto do código PHP, se necessário
include '../includes/footer.php';