<?php
session_start();
// Segurança: Apenas prestadores podem aceder
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'prestador') {
    header("Location: ../pages/login.php");
    exit();
}

include '../includes/header.php';
// ATENÇÃO: Estamos a incluir a nova navbar para a área logada
include '../includes/navbar_logged_in.php'; 
?>

<main class="d-flex">
    <?php include '../includes/sidebar.php'; // O sidebar agora faz parte do layout principal ?>

    <div class="container-fluid p-4">
        
        <div class="row">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Resumo do Prestador</h5>
                        
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                Avaliação Média
                                <span class="text-warning">
                                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i>
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                Serviços Concluídos
                                <span>125</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                Cancelamentos
                                <span>3</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <b>Saldo Atual</b>
                                <b class="text-success">R$ 450,00</b>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                 <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Próximos Agendamentos</h5>
                        
                        <div class="list-group list-group-flush">
                            <div class="list-group-item px-0">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">Maria Silva</h6>
                                    <small>18/09 – 14h00</small>
                                </div>
                                <p class="mb-2 text-muted">Limpeza de Manutenção</p>
                                <div>
                                    <button class="btn btn-outline-success btn-sm">Aceitar</button>
                                    <button class="btn btn-outline-danger btn-sm">Recusar</button>
                                </div>
                            </div>
                            <div class="list-group-item px-0">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">João Pedro</h6>
                                    <small>20/09 – 10h00</small>
                                </div>
                                <p class="mb-2 text-muted">Passadoria</p>
                                <div>
                                    <button class="btn btn-outline-success btn-sm">Aceitar</button>
                                    <button class="btn btn-outline-danger btn-sm">Recusar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>