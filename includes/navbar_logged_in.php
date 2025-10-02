<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container-fluid">
       <a class="navbar-brand" href="<?= BASE_URL ?>/index.php"><b></b>
        <i class="bi bi-star fs-3 me-2 bg-circle p-2 text-dark">StarClean</i></a>

        <div class="d-flex align-items-center">

            <div class="dropdown me-3">
                <a href="#" class="nav-link" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-bell fs-5"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown">
                    <li><h6 class="dropdown-header">Notificações</h6></li>
                    <li><a class="dropdown-item" href="#">
                        <small><b>Novo agendamento!</b></small><br>
                        <small class="text-muted">Maria Silva agendou uma limpeza.</small>
                    </a></li>
                    <li><a class="dropdown-item" href="#">
                        <small><b>Serviço concluído</b></small><br>
                        <small class="text-muted">O serviço para João Pedro foi finalizado.</small>
                    </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-center" href="#">Ver todas as notificações</a></li>
                </ul>
            </div>

            <div class="dropdown">
                <a href="#" class="nav-link" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user-circle fs-3"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li><h6 class="dropdown-header">Olá, <?= htmlspecialchars($_SESSION['usuario_nome']) ?>!</h6></li>
                    <li><a class="dropdown-item" href="<?= BASE_URL ?>/pages/perfil.php"><i class="fas fa-user-edit me-2"></i>Meu Perfil</a></li>
                    <li><a class="dropdown-item" href="<?= BASE_URL ?>/pages/configuracoes.php"><i class="fas fa-cog me-2"></i>Configurações</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="<?= BASE_URL ?>/pages/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Sair</a></li>
                </ul>
            </div>

        </div>
    </div>
</nav>