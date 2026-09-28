<?php
/**
 * Autenticación y roles del panel.
 *   admin    -> acceso total
 *   redactor -> solo reportajes (crear y editar los suyos)
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';   // deja disponible $pdo

// URL del panel (ej. /revista-admin), calculada según dónde esté instalado
if (!defined('ADMIN_URL')) {
    $raiz  = str_replace('\\', '/', rtrim((string) realpath($_SERVER['DOCUMENT_ROOT'] ?? ''), '/\\'));
    $panel = str_replace('\\', '/', (string) realpath(__DIR__ . '/..'));
    define('ADMIN_URL', $raiz !== '' && strpos($panel, $raiz) === 0 ? substr($panel, strlen($raiz)) : '/revista-admin');
}
// El portal público es la carpeta hermana "revista" (ej. /revista-admin -> /revista)
if (!defined('PORTAL_URL')) {
    define('PORTAL_URL', rtrim(str_replace('\\', '/', dirname(ADMIN_URL)), '/') . '/revista');
}
if (!defined('UPLOADS_PATH')) define('UPLOADS_PATH', realpath(__DIR__ . '/..') . '/uploads');

const ROLES_PANEL = ['admin', 'redactor'];

/* ---------- utilidades ---------- */
if (!function_exists('e')) {
    function e($str) { return htmlspecialchars((string) ($str ?? ''), ENT_QUOTES, 'UTF-8'); }
}

function flash($tipo, $mensaje) { $_SESSION['flash'] = ['tipo' => $tipo, 'mensaje' => $mensaje]; }

function obtener_flash() {
    if (isset($_SESSION['flash'])) { $m = $_SESSION['flash']; unset($_SESSION['flash']); return $m; }
    return null;
}

function csrf_token() {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}

function csrf_valido($token) {
    return is_string($token) && !empty($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $token);
}

/* ---------- sesión y rol ---------- */
function usuario_id_actual() { return (int) ($_SESSION['usuario_id'] ?? 0); }
function usuario_actual()    { return $_SESSION['usuario_nombre'] ?? 'Usuario'; }
function rol_actual()        { return $_SESSION['usuario_rol'] ?? ''; }
function es_admin()          { return rol_actual() === 'admin'; }

/** Exige haber iniciado sesión (cualquier rol válido). */
function exigir_sesion() {
    if (usuario_id_actual() === 0 || !in_array(rol_actual(), ROLES_PANEL, true)) {
        header('Location: ' . ADMIN_URL . '/login.php');
        exit;
    }
}

/** Exige rol administrador; el redactor vuelve al panel con un aviso. */
function exigir_admin() {
    exigir_sesion();
    if (!es_admin()) {
        flash('warning', 'No tienes permiso para entrar a esa sección.');
        header('Location: ' . ADMIN_URL . '/modules/reportajes/index.php');
        exit;
    }
}

/** Admin: cualquier reportaje. Redactor: solo los que él creó. */
function puede_editar_reportaje(array $reportaje) {
    return es_admin() || (int) $reportaje['usuario_id'] === usuario_id_actual();
}

/** Para scripts que tocan un reportaje por id: corta si no existe o no es del usuario (salvo admin). */
function exigir_dueno_reportaje(PDO $pdo, $reportaje_id) {
    exigir_sesion();
    $st = $pdo->prepare('SELECT id, usuario_id FROM reportajes WHERE id = ?');
    $st->execute([(int) $reportaje_id]);
    $r = $st->fetch();
    if (!$r || !puede_editar_reportaje($r)) {
        flash('warning', 'No tienes permiso sobre ese reportaje.');
        header('Location: ' . ADMIN_URL . '/modules/reportajes/index.php');
        exit;
    }
}
