<?php
session_start();

// Segurança: apenas clientes
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'cliente') {
    header("Location: ../pages/login.php");
    exit();
}

include '../includes/header.php';
include '../includes/navbar_logged_in.php';
?>

<main class="d-flex">
    <?php include '../includes/sidebar.php'; ?>

    <div class="container-fluid p-4">
        <h1 class="mb-4">Buscar Serviços</h1>
        <p>Encontre prestadores para o serviço que você precisa.</p>

        <!-- Formulário de busca -->
        <form method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" name="busca" class="form-control" placeholder="Digite o serviço (ex: Limpeza residencial, Limpeza de sofá...)">
                <button type="submit" class="btn btn-primary">Buscar</button>
            </div>
        </form>

        <!-- Lista de serviços (exemplo estático por enquanto) -->
        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title">Limpeza Residencial</h5>
                        <p class="card-text">Serviço completo de faxina e organização de casas e apartamentos.</p>
                        <a href="#" class="btn btn-success">Contratar</a>
                    </div>
                </div>
            </div>
            <!-- Repita para outros serviços -->
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
