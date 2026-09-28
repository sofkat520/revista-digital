<?php
require_once __DIR__ . '/includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_valido($_POST['csrf'] ?? null)) {
    header('Location: login.php?error=1');
    exit;
}

$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

$stmt = $pdo->prepare('SELECT * FROM usuarios WHERE email = :email LIMIT 1');
$stmt->execute([':email' => $email]);
$u = $stmt->fetch();

if ($u && password_verify($password, $u['password_hash']) && in_array($u['rol'], ROLES_PANEL, true)) {
    session_regenerate_id(true);
    $_SESSION['usuario_id']     = (int) $u['id'];
    $_SESSION['usuario_nombre'] = trim($u['nombres'] . ' ' . $u['ap_paterno']);
    $_SESSION['usuario_rol']    = $u['rol'];
    $_SESSION['usuario_email']  = $u['email'];

    // Admin entra al panel completo; redactor directo a sus reportajes
    header('Location: ' . ($u['rol'] === 'admin' ? 'index.php' : 'modules/reportajes/index.php'));
    exit;
}

header('Location: login.php?error=1');
exit;
