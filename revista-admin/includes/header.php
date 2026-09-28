<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Intentar cargar auth.php desde la carpeta local /includes/ o desde la raíz
if (file_exists(__DIR__ . '/auth.php')) {
    require_once __DIR__ . '/auth.php';
} elseif (file_exists(__DIR__ . '/../config/auth.php')) {
    require_once __DIR__ . '/../config/auth.php';
} elseif (file_exists(__DIR__ . '/../config/funciones.php')) {
    require_once __DIR__ . '/../config/funciones.php';
}

// 2. Cargar helpers de imagen si existen
if (file_exists(__DIR__ . '/imagen.php')) {
    require_once __DIR__ . '/imagen.php';
}

// 3. Control de acceso seguro
if (function_exists('exigir_sesion')) {
    exigir_sesion();
} else {
    if (!isset($_SESSION['admin_id']) && !isset($_SESSION['usuario']) && !isset($_SESSION['usuario_id'])) {
        header('Location: ' . (defined('ADMIN_URL') ? ADMIN_URL : '/revista-admin') . '/login.php');
        exit;
    }
}

// 4. Funciones auxiliares de escape si no existen
if (!function_exists('e')) {
    function e($str) {
        return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('usuario_actual')) {
    function usuario_actual() {
        return $_SESSION['usuario'] ?? $_SESSION['admin_nombre'] ?? 'Administrador';
    }
}

if (!function_exists('obtener_flash')) {
    function obtener_flash() {
        if (isset($_SESSION['flash'])) {
            $msg = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $msg;
        }
        return null;
    }
}

$tituloPagina = $tituloPagina ?? 'Panel DDP Noticias';
$moduloActivo = $moduloActivo ?? '';
$mensajeFlash = obtener_flash();
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($tituloPagina) ?> · DDP Noticias</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="<?= ADMIN_URL ?>/assets/css/admin.css">
</head>
<body>
<nav class="navbar navbar-expand-lg admin-topbar sticky-top">
  <div class="container-fluid">
    <button class="btn btn-sidebar d-lg-none me-2" type="button"
            data-bs-toggle="offcanvas" data-bs-target="#sidebarMovil" aria-label="Abrir menú">
      <i class="fa-solid fa-bars"></i>
    </button>
    <a class="navbar-brand mb-0" href="<?= ADMIN_URL ?>/index.php">
      DDP <span>Noticias</span>
    </a>
    <div class="ms-auto d-flex align-items-center gap-3">
      <a class="link-portal d-none d-sm-inline" href="<?= PORTAL_URL ?>/index.php" target="_blank">
        <i class="fa-solid fa-arrow-up-right-from-square me-1"></i>Ver el portal
      </a>
      <div class="dropdown">
        <button class="btn btn-usuario dropdown-toggle" data-bs-toggle="dropdown">
          <i class="fa-regular fa-user me-1"></i><?= e(usuario_actual()) ?>
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
          <?php if (es_admin()): ?>
          <li><a class="dropdown-item" href="<?= ADMIN_URL ?>/modules/usuarios/index.php">Usuarios</a></li>
          <li><hr class="dropdown-divider"></li>
          <?php endif; ?>
          <li><a class="dropdown-item text-danger" href="<?= ADMIN_URL ?>/logout.php">Cerrar sesión</a></li>
        </ul>
      </div>
    </div>
  </div>
</nav>

<div class="admin-shell">
  <?php include __DIR__ . '/sidebar.php'; ?>
  <main class="admin-main">
    <?php if ($mensajeFlash): ?>
      <div class="alert alert-<?= e($mensajeFlash['tipo']) ?> alert-dismissible fade show" role="alert">
        <?= e($mensajeFlash['mensaje']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
      </div>
    <?php endif; ?>
