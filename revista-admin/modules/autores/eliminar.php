<?php
require_once __DIR__ . '/../../includes/auth.php'; exigir_admin();
require_once __DIR__ . '/../../includes/auth.php';
exigir_sesion();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_valido($_POST['csrf'] ?? null)) {
    flash('danger', 'Petición no válida.');
    header('Location: index.php');
    exit;
}

$id = (int) ($_POST['id'] ?? 0);

$uso = $pdo->prepare('SELECT COUNT(*) FROM reportajes WHERE autor_id = ?');
$uso->execute([$id]);

if ($uso->fetchColumn() > 0) {
    flash('warning', 'Ese autor tiene reportajes publicados. Reasígnalos antes de borrarlo.');
} else {
    $del = $pdo->prepare('DELETE FROM autores WHERE id = ?');
    $del->execute([$id]);
    flash('success', 'Autor eliminado.');
}

header('Location: index.php');
exit;
