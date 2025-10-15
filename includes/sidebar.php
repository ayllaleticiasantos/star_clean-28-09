<button class="btn btn-primary d-md-none m-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu">
    <i class="fas fa-bars"></i> Menu
</button>

<div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="sidebarMenuLabel">Navegação</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0">
        <ul class="nav flex-column mt-3">
           <?php if (isset($_SESSION['usuario_tipo']) && ($_SESSION['usuario_tipo'] === 'admin' || $_SESSION['usuario_tipo'] === 'user')): ?>
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
                <li class="nav-item">
                    <a class="nav-link text-dark" href="<?= BASE_URL ?>/admin/gerir_agendamentos.php">
                        <i class="fas fa-calendar-check fa-fw me-2"></i>Gerir Agendamentos
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
                    <a class="nav-link text-dark" href="<?= BASE_URL ?>/prestador/gerir_agendamentos.php">
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
                    <a class="nav-link text-dark" href="<?= BASE_URL ?>/cliente/buscar_servicos.php">
                        <i class="fas fa-search fa-fw me-2"></i>Buscar Serviços
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="<?= BASE_URL ?>/cliente/meus_agendamentos.php">
                        <i class="fas fa-calendar-check fa-fw me-2"></i>Meus Agendamentos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="<?= BASE_URL ?>/cliente/gerir_enderecos.php">
                        <i class="fas fa-map-marker-alt fa-fw me-2"></i>Gerir Endereços
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 bg-white p-3 d-none d-md-block" style="min-height: 100vh;">
            <ul class="nav flex-column mt-3">
                <?php if (isset($_SESSION['usuario_tipo']) && ($_SESSION['usuario_tipo'] === 'admin' || $_SESSION['usuario_tipo'] === 'user')): ?>
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
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="<?= BASE_URL ?>/admin/gerir_agendamentos.php">
                            <i class="fas fa-calendar-check fa-fw me-2"></i>Gerir Agendamentos
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
                        <a class="nav-link text-dark" href="<?= BASE_URL ?>/prestador/gerir_agendamentos.php">
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
                        <a class="nav-link text-dark" href="<?= BASE_URL ?>/cliente/buscar_servicos.php">
                            <i class="fas fa-search fa-fw me-2"></i>Buscar Serviços
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="<?= BASE_URL ?>/cliente/meus_agendamentos.php">
                            <i class="fas fa-calendar-check fa-fw me-2"></i>Meus Agendamentos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="<?= BASE_URL ?>/cliente/gerir_enderecos.php">
                            <i class="fas fa-map-marker-alt fa-fw me-2"></i>Gerir Endereços
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>

        <!-- <div class="col-12 col-md-10 p-4">
            <h1>Bem-vindo ao seu Painel</h1>
            <p>Selecione uma opção no menu para começar.</p>
        </div> -->
    </div>  
</div>