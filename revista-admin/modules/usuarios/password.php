<?php
require_once __DIR__ . '/../../includes/auth.php';
exigir_sesion();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_valido($_POST['csrf'] ?? null)) {
    flash('danger', 'Petición no válida.');
    header('Location: index.php');
    exit;
}

$actual = $_POST['actual'] ?? '';
$nueva  = $_POST['nueva'] ?? '';
$id     = (int) ($_SESSION['admin_id'] ?? $_SESSION['usuario_id'] ?? 0);

if (strlen($nueva) < 8) {
    flash('danger', 'La nueva contraseña necesita al menos 8 caracteres.');
    header('Location: index.php');
    exit;
}

$sel = $pdo->prepare('SELECT password FROM usuarios WHERE id = ?');
$sel->execute([$id]);
$fila = $sel->fetch();

if (!$fila || !password_verify($actual, $fila['password'])) {
    flash('danger', 'La contraseña actual no coincide.');
    header('Location: index.php');
    exit;
}

$up = $pdo->prepare('UPDATE usuarios SET password = ? WHERE id = ?');
$up->execute([password_hash($nueva, PASSWORD_DEFAULT), $id]);

flash('success', 'Contraseña actualizada.');
header('Location: index.php');
exit;
