<?php
require_once __DIR__ . '/../../includes/auth.php'; exigir_admin();
/** Elimina la fila de la galería y borra el archivo físico. */
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/imagen.php';
exigir_sesion();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_valido($_POST['csrf'] ?? null)) {
    flash('danger', 'Petición no válida.');
    header('Location: index.php');
    exit;
}

$id = (int) ($_POST['id'] ?? 0);

$sel = $pdo->prepare('SELECT archivo FROM galeria WHERE id = ?');
$sel->execute([$id]);
$foto = $sel->fetch();

if (!$foto) {
    flash('warning', 'Esa imagen ya no está en la galería.');
    header('Location: index.php');
    exit;
}

$del = $pdo->prepare('DELETE FROM galeria WHERE id = ?');
$del->execute([$id]);

borrar_archivo_subido($foto['archivo'], 'galeria');

flash('success', 'Imagen eliminada.');
header('Location: index.php');
exit;
