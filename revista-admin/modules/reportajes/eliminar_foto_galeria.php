<?php
require_once __DIR__ . '/../../includes/auth.php'; exigir_sesion();
require_once '../../includes/auth.php';
require_once '../../config/database.php';

$id           = $_GET['id'] ?? null;
$reportaje_id = $_GET['reportaje_id'] ?? null;

if ($id && $reportaje_id) {
    exigir_dueno_reportaje($pdo, $reportaje_id);
    // Obtener el nombre del archivo para borrarlo del servidor
    $stmt = $pdo->prepare("SELECT url_foto FROM reportajes_fotos WHERE id = :id AND reportaje_id = :rid");
    $stmt->execute([':id' => $id, ':rid' => $reportaje_id]);
    $foto = $stmt->fetchColumn();

    if ($foto && file_exists('../../uploads/fotos/' . $foto)) {
        unlink('../../uploads/fotos/' . $foto);
    }

    // Eliminar registro de la BD
    $stmt_del = $pdo->prepare("DELETE FROM reportajes_fotos WHERE id = :id AND reportaje_id = :rid");
    $stmt_del->execute([':id' => $id, ':rid' => $reportaje_id]);
}

header('Location: editar.php?id=' . $reportaje_id);
exit;