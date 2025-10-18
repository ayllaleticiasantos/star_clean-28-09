<?php
session_start();
require_once '../config/db.php';

// Segurança: Apenas clientes podem acessar a esta página
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'cliente') {
    header("Location: ../pages/login.php");
    exit();
}

$mensagem = '';
$id_cliente = $_SESSION['usuario_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cep = trim($_POST['cep']);
    $logradouro = trim($_POST['logradouro']);
    $numero = trim($_POST['numero']);
    $complemento = trim($_POST['complemento']);
    $bairro = trim($_POST['bairro']);
    $cidade = trim($_POST['cidade']);
    $uf = trim($_POST['uf']);

    try {
        $pdo = obterConexaoPDO();
        $stmt = $pdo->prepare(
            "INSERT INTO Endereco (Cliente_id, Prestador_id, cep, logradouro, numero, complemento, bairro, cidade, uf)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        // O valor de Prestador_id pode ser NULL para clientes, dependendo da sua regra de negócio
        $stmt->execute([$id_cliente, NULL, $cep, $logradouro, $numero, $complemento, $bairro, $cidade, $uf]);

        $_SESSION['mensagem_sucesso'] = "Endereço adicionado com sucesso!";
        header("Location: gerir_enderecos.php");
        exit();
    } catch (PDOException $e) {
        $mensagem = '<div class="alert alert-danger">Erro ao adicionar o endereço. Tente novamente.</div>';
        // Para depuração: error_log($e->getMessage());
    }
}

include '../includes/header.php';
include '../includes/navbar_logged_in.php';
?>

<main class="d-flex">
    <?php include '../includes/sidebar.php'; ?>

    <div class="container-fluid p-4">
        <h1 class="mb-4">Adicionar Novo Endereço</h1>
        <hr>

        <?php if (!empty($mensagem)) { echo $mensagem; } ?>

        <div class="card shadow-sm">
            <div class="card-body">
                <form action="adicionar_endereco.php" method="post">
                    <div class="mb-3">
                        <label for="cep" class="form-label">CEP:</label>
                        <input type="text" class="form-control" id="cep" name="cep" required>
                    </div>
                    <div class="mb-3">
                        <label for="logradouro" class="form-label">Logradouro:</label>
                        <input type="text" class="form-control" id="logradouro" name="logradouro" required>
                    </div>
                    <div class="mb-3">
                        <label for="numero" class="form-label">Número:</label>
                        <input type="text" class="form-control" id="numero" name="numero" required>
                    </div>
                    <div class="mb-3">
                        <label for="complemento" class="form-label">Complemento (opcional):</label>
                        <input type="text" class="form-control" id="complemento" name="complemento">
                    </div>
                    <div class="mb-3">
                        <label for="bairro" class="form-label">Bairro:</label>
                        <input type="text" class="form-control" id="bairro" name="bairro" required>
                    </div>
                    <div class="row">
                        <div class="col-md-9 mb-3">
                            <label for="cidade" class="form-label">Cidade:</label>
                            <input type="text" class="form-control" id="cidade" name="cidade" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="uf" class="form-label">UF:</label>
                            <input type="text" class="form-control" id="uf" name="uf" required maxlength="2">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Salvar Endereço</button>
                    <a href="gerir_enderecos.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cepInput = document.getElementById('cep');

        cepInput.addEventListener('blur', function() {
            let cep = cepInput.value.replace(/\D/g, ''); // Remove caracteres não-numéricos

            if (cep.length === 8) {
                fetch(`https://viacep.com.br/ws/${cep}/json/`)
                    .then(response => response.json())
                    .then(data => {
                        if (!data.erro) {
                            document.getElementById('logradouro').value = data.logradouro;
                            document.getElementById('bairro').value = data.bairro;
                            document.getElementById('cidade').value = data.localidade;
                            document.getElementById('uf').value = data.uf;
                        } else {
                            // Limpa os campos se o CEP for inválido
                            alert('CEP não encontrado.');
                            limparCamposEndereco();
                        }
                    })
                    .catch(() => {
                        alert('Ocorreu um erro ao buscar o CEP.');
                        limparCamposEndereco();
                    });
            } else if (cep.length > 0) {
                alert('Formato de CEP inválido.');
                limparCamposEndereco();
            }
        });

        function limparCamposEndereco() {
            document.getElementById('logradouro').value = '';
            document.getElementById('bairro').value = '';
            document.getElementById('cidade').value = '';
            document.getElementById('uf').value = '';
            document.getElementById('numero').value = '';
            document.getElementById('complemento').value = '';
        }
    });
</script>

<?php include '../includes/footer.php'; ?>