<?php
session_start();
require_once '../config/db.php';

$mensagem = '';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Campos comuns que sempre virão do formulário
    $tipo = $_POST['tipo'] ?? '';
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

                    if (empty($nome) || empty($sobrenome) || empty($data_nascimento) || empty($cpf)) {
                        $mensagem = '<div class="alert alert-danger">Todos os campos do cliente são obrigatórios!</div>';
                        break;
                    }

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

                    if (empty($nomeRazao) || empty($cpfCnpj) || empty($especialidade)) {
                        $mensagem = '<div class="alert alert-danger">Nome, CPF e Especialidade são obrigatórios!</div>';
                        break;
                    }

                    $stmt = $pdo->prepare("SELECT id FROM Prestador WHERE email = ? OR cpf_cnpj = ?");
                    $stmt->execute([$email, $cpfCnpj]);

                    if ($stmt->fetch()) {
                        $mensagem = '<div class="alert alert-danger">E-mail ou CPF/CNPJ já cadastrados!</div>';
                    } else {
                        $admin_id_responsavel = 1; // ID do admin padrão

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

<div class="container d-flex justify-content-center align-items-center"
    style="min-height: 80vh; margin-top: 20px; margin-bottom: 20px;">
    <div class="card p-4 shadow-sm" style="width: 100%; max-width: 600px;">
        <h3 class="text-center mb-4">Cadastro de Novo Usuário</h3>

        <?php if (!empty($mensagem)) {
            echo $mensagem;
        } ?>

        <form action="cadastro.php" method="post" id="formCadastro">
            <div class="mb-3">
                <label for="email" class="form-label">E-mail:</label>
                <input type="email" class="form-control" name="email" id="email" placeholder="email@exemplo.com"
                    required>
            </div>
            <div class="mb-3">
                <label for="senha" class="form-label">Senha:</label>
                <input type="password" class="form-control" name="senha" id="senha" placeholder="Crie uma senha forte"
                    required>
            </div>
            <div class="mb-3">
                <label class="form-label">Tipo de conta:</label>
                <div class="form-check"><input class="form-check-input" type="radio" name="tipo" id="tipoCliente"
                        value="cliente" checked><label class="form-check-label" for="tipoCliente">Sou Cliente</label>
                </div>
                <div class="form-check"><input class="form-check-input" type="radio" name="tipo" id="tipoPrestador"
                        value="prestador"><label class="form-check-label" for="tipoPrestador">Sou Prestador</label>
                </div>
            </div>

            <div id="camposCliente">
                <div class="mb-3"><label for="cliente_nome" class="form-label">Nome:</label><input type="text"
                        class="form-control" name="cliente_nome" id="cliente_nome"
                        placeholder="Digite seu nome completo"></div>
                <div class="mb-3"><label for="cliente_sobrenome" class="form-label">Sobrenome:</label><input type="text"
                        class="form-control" name="cliente_sobrenome" id="cliente_sobrenome"
                        placeholder="Digite seu sobrenome"></div>
                <div class="mb-3">
                    <label for="cpf" class="form-label">CPF:</label>
                    <input type="text" class="form-control" name="cpf" id="cpf" placeholder="000.000.000-00"
                        maxlength="14">
                    <div id="cpfError" class="text-danger mt-1" style="display: none; font-size: 0.9em;">CPF inválido.
                    </div>
                </div>
                <div class="mb-3"><label for="cliente_telefone" class="form-label">Telefone:</label><input type="tel"
                        class="form-control" name="cliente_telefone" id="cliente_telefone" placeholder="(XX) XXXXX-XXXX"
                        maxlength="15"></div>
                <div class="mb-3"><label for="data_nascimento" class="form-label">Data de Nascimento:</label><input
                        type="date" class="form-control" name="data_nascimento" id="data_nascimento"></div>
            </div>

            <div id="camposPrestador" style="display: none;">
                <div class="mb-3"><label for="prestador_nome_razao" class="form-label"
                        placeholder="Dígite Seu Nome ou Razão Social">Nome:</label><input
                        type="text" class="form-control" name="prestador_nome_razao" id="prestador_nome_razao" placeholder="Dígite Seu Nome ou Razão Social"></div>
                <div class="mb-3"><label for="prestador_sobrenome_fantasia" class="form-label"
                        placeholder="Digite Seu Sobrenome ou Nome Fantasia">Sobrenome:</label><input
                        type="text" class="form-control" name="prestador_sobrenome_fantasia"
                        id="prestador_sobrenome_fantasia" placeholder="Digite Seu Sobrenome ou Nome Fantasia"></div>

            <div class="mb-3">
                <label class="form-label">Tipo de Documento:</label>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="tipo_documento_prestador" id="tipoDocCpf"
                        value="cpf" checked>
                    <label class="form-check-label" for="tipoDocCpf">Pessoa Física (CPF)</label>
                </div>
                <!-- <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="tipo_documento_prestador" id="tipoDocCnpj"
                        value="cnpj">
                    <label class="form-check-label" for="tipoDocCnpj">Pessoa Jurídica (CNPJ)</label>
                </div> -->
            </div>

            <div class="mb-3">
                <label for="cpf_cnpj" class="form-label" id="label_cpf_cnpj">CPF:</label>
                <input type="text" class="form-control" name="cpf_cnpj" id="cpf_cnpj" placeholder="000.000.000-00"
                    maxlength="14">
                <div id="docError" class="text-danger mt-1" style="display: none; font-size: 0.9em;">Documento inválido.
                </div>
            </div>

            <div class="mb-3"><label for="prestador_telefone" class="form-label">Telefone:</label><input type="tel"
                    class="form-control" name="prestador_telefone" id="prestador_telefone" placeholder="(XX) XXXXX-XXXX"
                    maxlength="15"></div>
            <div class="mb-3"><label for="especialidade" class="form-label"
                    placeholder="Digite Sua Especialidade">Especialidade:</label><input type="text" class="form-control"
                    name="especialidade" id="especialidade" placeholder="Ex: Limpeza residencial"></div>
            <div class="mb-3"><label for="descricao" class="form-label"
                    placeholder="Digite uma descrição">Descrição:</label><textarea class="form-control" name="descricao"
                    id="descricao" placeholder="Fale um pouco sobre seus serviços"></textarea></div>
    </div>

    <button type="submit" class="btn btn-primary w-100">Cadastrar</button>
    </form>
</div>
</div>

<script>
    // --- LÓGICA GERAL DO FORMULÁRIO ---
    document.addEventListener('DOMContentLoaded', function () {
        const radios = document.querySelectorAll('input[name="tipo"]');
        const camposCliente = document.getElementById('camposCliente');
        const camposPrestador = document.getElementById('camposPrestador');

        function toggleCampos() {
            const tipoSelecionado = document.querySelector('input[name="tipo"]:checked').value;
            camposCliente.style.display = tipoSelecionado === 'cliente' ? 'block' : 'none';
            camposPrestador.style.display = tipoSelecionado === 'prestador' ? 'block' : 'none';
        }
        radios.forEach(radio => radio.addEventListener('change', toggleCampos));
        toggleCampos();
    });

    // --- MÁSCARA DE TELEFONE ---
    function mascaraTelefone(evento) {
        if (evento.key === "Backspace") return;
        let valor = evento.target.value.replace(/\D/g, '');
        valor = valor.replace(/^(\d{2})(\d)/g, '($1) $2');
        valor = valor.replace(/(\d)(\d{4})$/, '$1-$2');
        evento.target.value = valor;
    }
    const inputTelefoneCliente = document.getElementById('cliente_telefone');
    const inputTelefonePrestador = document.getElementById('prestador_telefone');
    if (inputTelefoneCliente) inputTelefoneCliente.addEventListener('keyup', mascaraTelefone);
    if (inputTelefonePrestador) inputTelefonePrestador.addEventListener('keyup', mascaraTelefone);

    // --- FUNÇÕES DE MÁSCARA E VALIDAÇÃO (CPF E CNPJ) ---
    function mascaraCPF(evento) {
        if (evento.key === "Backspace") return;
        let valor = evento.target.value.replace(/\D/g, '');
        valor = valor.replace(/(\d{3})(\d)/, '$1.$2');
        valor = valor.replace(/(\d{3})(\d)/, '$1.$2');
        valor = valor.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
        evento.target.value = valor;
    }

    function validaCPF(cpf) {
        cpf = cpf.replace(/[^\d]+/g, '');
        if (cpf === '' || cpf.length !== 11 || /^(\d)\1{10}$/.test(cpf)) return false;
        let soma = 0, resto;
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

    function mascaraCNPJ(evento) {
        if (evento.key === "Backspace") return;
        let valor = evento.target.value.replace(/\D/g, '');
        valor = valor.replace(/^(\d{2})(\d)/, '$1.$2');
        valor = valor.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
        valor = valor.replace(/\.(\d{3})(\d)/, '.$1/$2');
        valor = valor.replace(/(\d{4})(\d)/, '$1-$2');
        evento.target.value = valor;
    }

    function validaCNPJ(cnpj) {
        cnpj = cnpj.replace(/[^\d]+/g, '');
        if (cnpj === '' || cnpj.length !== 14 || /^(\d)\1{13}$/.test(cnpj)) return false;
        let tamanho = cnpj.length - 2, numeros = cnpj.substring(0, tamanho), digitos = cnpj.substring(tamanho), soma = 0, pos = tamanho - 7;
        for (let i = tamanho; i >= 1; i--) {
            soma += numeros.charAt(tamanho - i) * pos--;
            if (pos < 2) pos = 9;
        }
        let resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado != digitos.charAt(0)) return false;
        tamanho = tamanho + 1;
        numeros = cnpj.substring(0, tamanho);
        soma = 0;
        pos = tamanho - 7;
        for (let i = tamanho; i >= 1; i--) {
            soma += numeros.charAt(tamanho - i) * pos--;
            if (pos < 2) pos = 9;
        }
        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado != digitos.charAt(1)) return false;
        return true;
    }

    // --- LÓGICA DO CAMPO DE CPF (CLIENTE) ---
    const inputCpfCliente = document.getElementById('cpf');
    const cpfError = document.getElementById('cpfError');
    if (inputCpfCliente) {
        inputCpfCliente.addEventListener('keyup', mascaraCPF);
        inputCpfCliente.addEventListener('blur', () => {
            if (inputCpfCliente.value.length > 0 && !validaCPF(inputCpfCliente.value)) {
                cpfError.style.display = 'block';
                inputCpfCliente.classList.add('is-invalid');
            } else {
                cpfError.style.display = 'none';
                inputCpfCliente.classList.remove('is-invalid');
            }
        });
    }

    // --- LÓGICA DINÂMICA PARA O CAMPO CPF/CNPJ (PRESTADOR) ---
    const tipoDocRadios = document.querySelectorAll('input[name="tipo_documento_prestador"]');
    const inputCpfCnpjPrestador = document.getElementById('cpf_cnpj');
    const labelCpfCnpj = document.getElementById('label_cpf_cnpj');
    const docError = document.getElementById('docError');

    function configurarCampoDocumento() {
        const tipoSelecionado = document.querySelector('input[name="tipo_documento_prestador"]:checked').value;

        inputCpfCnpjPrestador.removeEventListener('keyup', mascaraCPF);
        inputCpfCnpjPrestador.removeEventListener('keyup', mascaraCNPJ);
        inputCpfCnpjPrestador.value = '';
        docError.style.display = 'none';
        inputCpfCnpjPrestador.classList.remove('is-invalid');

        if (tipoSelecionado === 'cpf') {
            labelCpfCnpj.textContent = 'CPF:';
            inputCpfCnpjPrestador.placeholder = '000.000.000-00';
            inputCpfCnpjPrestador.maxLength = 14;
            inputCpfCnpjPrestador.addEventListener('keyup', mascaraCPF);
        } else {
            labelCpfCnpj.textContent = 'CNPJ:';
            inputCpfCnpjPrestador.placeholder = '00.000.000/0000-00';
            inputCpfCnpjPrestador.maxLength = 18;
            inputCpfCnpjPrestador.addEventListener('keyup', mascaraCNPJ);
        }
    }

    function validarDocumentoPrestador() {
        const tipoSelecionado = document.querySelector('input[name="tipo_documento_prestador"]:checked').value;
        const ehValido = (tipoSelecionado === 'cpf') ? validaCPF(inputCpfCnpjPrestador.value) : validaCNPJ(inputCpfCnpjPrestador.value);

        if (inputCpfCnpjPrestador.value.length > 0 && !ehValido) {
            docError.style.display = 'block';
            inputCpfCnpjPrestador.classList.add('is-invalid');
        } else {
            docError.style.display = 'none';
            inputCpfCnpjPrestador.classList.remove('is-invalid');
        }
    }

    tipoDocRadios.forEach(radio => radio.addEventListener('change', configurarCampoDocumento));
    inputCpfCnpjPrestador.addEventListener('blur', validarDocumentoPrestador);
    configurarCampoDocumento();

    // --- VALIDAÇÃO GERAL DO FORMULÁRIO ANTES DO ENVIO ---
    const formCadastro = document.getElementById('formCadastro');
    if (formCadastro) {
        formCadastro.addEventListener('submit', function (evento) {
            const camposClienteVisivel = document.getElementById('camposCliente').style.display !== 'none';
            const camposPrestadorVisivel = document.getElementById('camposPrestador').style.display !== 'none';

            if (camposClienteVisivel && inputCpfCliente.value.length > 0 && !validaCPF(inputCpfCliente.value)) {
                evento.preventDefault();
                alert('Por favor, corrija o CPF do cliente antes de continuar.');
                inputCpfCliente.focus();
            }

            if (camposPrestadorVisivel) {
                const tipoDoc = document.querySelector('input[name="tipo_documento_prestador"]:checked').value;
                const docValido = (tipoDoc === 'cpf') ? validaCPF(inputCpfCnpjPrestador.value) : validaCNPJ(inputCpfCnpjPrestador.value);
                if (inputCpfCnpjPrestador.value.length > 0 && !docValido) {
                    evento.preventDefault();
                    alert('Por favor, corrija o ' + tipoDoc.toUpperCase() + ' do prestador antes de continuar.');
                    inputCpfCnpjPrestador.focus();
                }
            }
        });
    }
</script>

<?php
include '../includes/footer.php';
?>