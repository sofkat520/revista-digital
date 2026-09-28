<?php
require_once __DIR__ . '/../../includes/auth.php'; exigir_admin();
require_once __DIR__ . '/../../includes/auth.php';
exigir_sesion();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_valido($_POST['csrf'] ?? null)) {
    flash('danger', 'Petición no válida.');
    header('Location: index.php');
    exit;
}

$id  = (int) ($_POST['id'] ?? 0);
$mio = (int) ($_SESSION['admin_id'] ?? 0);

if ($id === $mio) {
    flash('warning', 'No puedes borrar tu propia cuenta mientras la usas.');
} else {
    $del = $pdo->prepare('DELETE FROM usuarios WHERE id = ?');
    $del->execute([$id]);
    flash('success', 'Acceso retirado.');
}

header('Location: index.php');
exit;
