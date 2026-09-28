<div class="app-header-inner">  
    <div class="container-fluid py-2">
        <div class="app-header-content"> 
            <div class="row justify-content-between align-items-center">
                
                <!-- Botón Hamburguesa para Móvil -->
                <div class="col-auto">
                    <a id="sidepanel-toggler" class="sidepanel-toggler d-inline-block d-xl-none" href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" role="img">
                            <title>Menú</title>
                            <path stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2" d="M4 7h22M4 15h22M4 23h22"></path>
                        </svg>
                    </a>
                </div>
                
                <div class="app-utilities col-auto">
                    <div class="app-utility-item app-user-dropdown dropdown">
                        <span class="text-muted small me-2">Hola, <strong><?= e(usuario_actual()) ?></strong> <span class="badge bg-secondary"><?= es_admin() ? 'Administrador' : 'Redactor' ?></span></span>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Menú Lateral Sidebar -->
<div id="app-sidepanel" class="app-sidepanel">
    <div id="sidepanel-drop" class="sidepanel-drop"></div>
    <div class="sidepanel-inner d-flex flex-column">
        <a href="#" id="sidepanel-close" class="sidepanel-close d-xl-none">&times;</a>
        
        <div class="app-branding text-center py-3">
            <a class="app-logo" href="<?= ADMIN_URL ?>/index.php">
                <span class="logo-text fw-bold">D&D ADMIN</span>
            </a>
        </div>
        
        <nav id="app-nav-main" class="app-nav app-nav-main flex-grow-1">
            <ul class="app-menu list-unstyled accordion" id="menu-accordion">
                
                <li class="nav-item">
                    <a class="nav-link" href="<?= ADMIN_URL ?>/modules/reportajes/index.php">
                        <span class="nav-icon"><i class="fas fa-newspaper"></i></span>
                        <span class="nav-link-text">Reportajes</span>
                    </a>
                </li>

                <?php if (es_admin()): ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= ADMIN_URL ?>/modules/boletines/index.php">
                        <span class="nav-icon"><i class="fas fa-newspaper"></i></span>
                        <span class="nav-link-text">Boletines</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?= ADMIN_URL ?>/modules/autores/index.php">
                        <span class="nav-icon"><i class="fas fa-users"></i></span>
                        <span class="nav-link-text">Autores</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?= ADMIN_URL ?>/modules/noticias/index.php">
                        <span class="nav-icon"><i class="fas fa-bullhorn"></i></span>
                        <span class="nav-link-text">Noticias</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?= ADMIN_URL ?>/modules/multimedia/index.php">
                        <span class="nav-icon"><i class="fas fa-photo-video"></i></span>
                        <span class="nav-link-text">Multimedia</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?= ADMIN_URL ?>/modules/usuarios/index.php">
                        <span class="nav-icon"><i class="fas fa-user-shield"></i></span>
                        <span class="nav-link-text">Usuarios</span>
                    </a>
                </li>
                <?php endif; ?>

                <li class="nav-item mt-4">
                    <a class="nav-link text-danger" href="<?= ADMIN_URL ?>/logout.php">
                        <span class="nav-icon"><i class="fas fa-sign-out-alt text-danger"></i></span>
                        <span class="nav-link-text fw-bold">Cerrar Sesión</span>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</div>