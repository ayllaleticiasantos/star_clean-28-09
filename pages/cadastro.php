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

        <?php if (!empty($mensagem)) {
            echo $mensagem;
        } ?>

        <form action="cadastro.php" method="post">
            <div class="mb-3">
                <label for="email" class="form-label" placeholder=" Digite seu e-mail">E-mail:</label>
                <input type="email" class="form-control" name="email" id="email" placeholder="email@exemplo.com"
                    required>
            </div>
            <div class="mb-3">
                <label for="senha" class="form-label" placeholder=" Digite sua senha">Senha:</label>
                <input type="password" class="form-control" name="senha" id="senha" placeholder="Digite sua senha"
                    required>
            </div>
            <div class="mb-3">
                <label class="form-label">Tipo de conta:</label>
                <div class="form-check"><input class="form-check-input" type="radio" name="tipo" id="tipoCliente"
                        value="cliente" checked><label class="form-check-label" for="tipoCliente">Cliente</label></div>
                <div class="form-check"><input class="form-check-input" type="radio" name="tipo" id="tipoPrestador"
                        value="prestador"><label class="form-check-label" for="tipoPrestador">Prestador</label></div>
            </div>

            <div id="camposCliente">
                <div class="mb-3"><label for="cliente_nome" class="form-label"
                        placeholder="Digite seu nome">Nome:</label>
                    <input type="text" class="form-control" name="cliente_nome" id="cliente_nome"
                        placeholder="Digite seu nome">
                </div>
                <div class="mb-3"><label for="cliente_sobrenome" class="form-label"
                        placeholder="Digite seu sobrenome">Sobrenome:</label><input type="text" class="form-control"
                        name="cliente_sobrenome" id="cliente_sobrenome" placeholder="Digite seu sobrenome"></div>
                <div class="mb-3"><label for="cpf" class="form-label" placeholder="Digite seu CPF">CPF:</label><input
                        type="text" class="form-control" name="cpf" id="cpf" placeholder="Digite seu CPF"></div>
                <div class="mb-3"><label for="cliente_telefone" class="form-label"
                        placeholder="Digite seu telefone">Telefone:</label><input type="text" class="form-control"
                        name="cliente_telefone" id="cliente_telefone" placeholder="(XX) XXXXX-XXXX"></div>
                <div class="mb-3"><label for="data_nascimento" class="form-label">Data de Nascimento:</label><input
                        type="date" class="form-control" name="data_nascimento" id="data_nascimento"></div>
            </div>

            <div id="camposPrestador" style="display: none;">
                <div class="mb-3"><label for="prestador_nome_razao" class="form-label" placeholder="Digite seu nome / razão social">Nome / Razão
                        Social:</label><input type="text" class="form-control" name="prestador_nome_razao"
                        id="prestador_nome_razao" placeholder="Digite seu nome / razão social"></div>
                <div class="mb-3"><label for="prestador_sobrenome_fantasia" class="form-label" placeholder="Digite seu sobrenome / nome fantasia">Sobrenome / Nome
                        Fantasia:</label><input type="text" class="form-control" name="prestador_sobrenome_fantasia"
                        id="prestador_sobrenome_fantasia" placeholder="Digite seu sobrenome / nome fantasia"></div>
                <div class="mb-3"><label for="cpf_cnpj" class="form-label">CPF/CNPJ:</label><input type="text"
                        class="form-control" name="cpf_cnpj" id="cpf_cnpj" placeholder="Digite seu CPF/CNPJ"></div>
                <div class="mb-3"><label for="prestador_telefone" class="form-label"placeholder="Dígite seu telefone:">Telefone:</label><input type="text"
                        class="form-control" name="prestador_telefone" id="prestador_telefone" placeholder="(XX) XXXXX-XXXX"></div>
                <div class="mb-3"><label for="especialidade" class="form-label">Especialidade:</label><input type="text"
                        class="form-control" name="especialidade" id="especialidade"></div>
                <div class="mb-3"><label for="descricao" class="form-label">Descrição:</label><textarea
                        class="form-control" name="descricao" id="descricao"></textarea></div>
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
    // --- CÓDIGO DA MÁSCARA DE TELEFONE ---
// 1. Função que aplica a máscara
function mascaraTelefone(evento) {
    const telefoneInput = evento.target;
    // Impede que o evento seja disparado se a tecla pressionada for backspace
    if (evento.key === "Backspace") return;

    let valor = telefoneInput.value.replace(/\D/g, ''); // Remove tudo que não é dígito
    valor = valor.replace(/^(\d{2})(\d)/g, '($1) $2'); // Coloca parênteses em volta dos dois primeiros dígitos
    valor = valor.replace(/(\d)(\d{4})$/, '$1-$2');    // Coloca hífen antes dos últimos 4 dígitos
    telefoneInput.value = valor;
}

// 2. Seleciona os campos de telefone
const inputTelefoneCliente = document.getElementById('cliente_telefone');
const inputTelefonePrestador = document.getElementById('prestador_telefone');

// 3. Adiciona o "ouvinte" de evento que chama a função de máscara
if (inputTelefoneCliente) {
    inputTelefoneCliente.addEventListener('keyup', mascaraTelefone);
}
if (inputTelefonePrestador) {
    inputTelefonePrestador.addEventListener('keyup', mascaraTelefone);
}
// --- MÁSCARA E VALIDAÇÃO DE CPF ---

const inputCpf = document.getElementById('cpf');
const formCadastro = document.getElementById('formCadastro');
const cpfError = document.getElementById('cpfError');

// 1. Função que aplica a máscara de CPF
function mascaraCPF(evento) {
    if (evento.key === "Backspace") return;
    let valor = inputCpf.value.replace(/\D/g, '');
    valor = valor.replace(/(\d{3})(\d)/, '$1.$2');
    valor = valor.replace(/(\d{3})(\d)/, '$1.$2');
    valor = valor.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
    inputCpf.value = valor;
}

// 2. Função que valida o CPF
function validaCPF(cpf) {
    cpf = cpf.replace(/[^\d]+/g, '');
    if (cpf === '' || cpf.length !== 11 || /^(\d)\1{10}$/.test(cpf)) return false;
    let soma = 0;
    let resto;
    for (let i = 1; i <= 9; i++) soma += parseInt(cpf.substring(i - 1, i)) * (11 - i);
    resto = (soma * 10) % 11;
    if ((resto === 10) || (resto === 11)) resto = 0;
    if (resto !== parseInt(cpf.substring(9, 10))) return false;
    soma = 0;
    for (let i = 1; i <= 10; i++) soma += parseInt(cpf.substring(i - 1, i)) * (12 - i);
    resto = (soma * 10) % 11;
    if ((resto === 10) || (resto === 11)) resto = 0;
    if (resto !== parseInt(cpf.substring(10, 11))) return false;
    return true;
}

// 3. Adiciona os "ouvintes" de evento
if (inputCpf) {
    // Aplica a máscara ao digitar
    inputCpf.addEventListener('keyup', mascaraCPF);
    
    // Valida o CPF quando o utilizador sai do campo
    inputCpf.addEventListener('blur', () => {
        if (inputCpf.value.length > 0 && !validaCPF(inputCpf.value)) {
            cpfError.style.display = 'block'; // Mostra a mensagem de erro
            inputCpf.classList.add('is-invalid'); // Adiciona a classe de erro do Bootstrap
        } else {
            cpfError.style.display = 'none'; // Esconde a mensagem de erro
            inputCpf.classList.remove('is-invalid'); // Remove a classe de erro
        }
    });
}

// 4. Impede o envio do formulário se o CPF for inválido
if (formCadastro) {
    formCadastro.addEventListener('submit', function (evento) {
        // Verifica o CPF apenas se os campos do cliente estiverem visíveis
        const camposClienteVisivel = document.getElementById('camposCliente').style.display !== 'none';

        if (camposClienteVisivel && inputCpf.value.length > 0 && !validaCPF(inputCpf.value)) {
            evento.preventDefault(); // Impede o envio do formulário
            cpfError.style.display = 'block';
            inputCpf.classList.add('is-invalid');
            alert('Por favor, corrija o CPF antes de continuar.');
        }
    });
}
</script>
<?php
// Resto do código PHP, se necessário
include '../includes/footer.php';
?>