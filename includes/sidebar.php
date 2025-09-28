<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 bg-white p-3" style="min-height: 100vh;">
            <ul class="nav flex-column mt-3">
                
                <?php if (isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] === 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link text-dark active" href="<?= BASE_URL ?>/admin/dashboard.php">
                            <i class="fas fa-chart-line fa-fw me-2"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="<?= BASE_URL ?>/admin/gerir_utilizadores.php">
                            <i class="fas fa-users fa-fw me-2"></i>Gerir Utilizadores
                        </a>
                    </li>
                <?php endif; ?>

                <?php if (isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] === 'prestador'): ?>
                    <li class="nav-item">
                        <a class="nav-link text-dark active" href="<?= BASE_URL ?>/prestador/dashboard.php">
                            <i class="fas fa-chart-line fa-fw me-2"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="<?= BASE_URL ?>/prestador/gerir_servicos.php">
                            <i class="fas fa-briefcase fa-fw me-2"></i>Meus Serviços
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="#">
                            <i class="fas fa-calendar-alt fa-fw me-2"></i>Agendamentos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="#">
                            <i class="fas fa-comments fa-fw me-2"></i>Mensagens
                        </a>
                    </li>
                <?php endif; ?>

                <?php if (isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] === 'cliente'): ?>
                    <li class="nav-item">
                        <a class="nav-link text-dark active" href="<?= BASE_URL ?>/cliente/dashboard.php">
                            <i class="fas fa-tachometer-alt fa-fw me-2"></i>Meu Painel
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="#">
                            <i class="fas fa-search fa-fw me-2"></i>Buscar Serviços
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="#">
                            <i class="fas fa-calendar-check fa-fw me-2"></i>Meus Agendamentos
                        </a>
                    </li>
                    <?php endif; ?>
                 </ul>
        </div>

        <div class="col-md-10 p-4">