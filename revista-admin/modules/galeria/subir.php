<?php
require_once __DIR__ . '/../../includes/auth.php'; exigir_admin();
/** Procesa y comprime la imagen subida a la galería general. */
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/imagen.php';
exigir_sesion();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_valido($_POST['csrf'] ?? null)) {
    flash('danger', 'Petición no válida.');
    header('Location: index.php');
    exit;
}

$res = procesar_imagen($_FILES['archivo'] ?? [], UPLOADS_PATH . '/galeria', 'gal');

if (!$res['ok']) {
    flash('danger', $res['error']);
    header('Location: index.php');
    exit;
}

$titulo = trim($_POST['titulo'] ?? '');

try {
    $ins = $pdo->prepare('INSERT INTO galeria (titulo, archivo) VALUES (?,?)');
    $ins->execute([$titulo !== '' ? $titulo : null, $res['archivo']]);
    flash('success', 'Imagen subida. Ya puedes copiar su URL.');
} catch (PDOException $e) {
    borrar_archivo_subido($res['archivo'], 'galeria');
    flash('danger', 'La imagen no se pudo registrar en la base de datos.');
}

header('Location: index.php');
exit;
