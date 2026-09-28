<?php
require_once __DIR__ . '/../../includes/auth.php'; exigir_admin();
if (!isset($_SESSION['admin_id']) && !isset($_SESSION['usuario']) && !isset($_SESSION['usuario_id'])) {
    header('Location: ../../login.php');
    exit;
}

require_once '../../config/database.php';

$id = $_GET['id'] ?? null;

if ($id) {
    // 1. Obtener archivos vinculados
    $stmt = $pdo->prepare("SELECT foto_principal, pdf_adjunto FROM reportajes WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $rep = $stmt->fetch();

    if ($rep) {
        // Borrar foto principal si existe
        if (!empty($rep['foto_principal'])) {
            @unlink('../../uploads/fotos/' . $rep['foto_principal']);
        }
        // Borrar PDF de la carpeta pdf si existe
        if (!empty($rep['pdf_adjunto'])) {
            @unlink('../../uploads/pdf/' . $rep['pdf_adjunto']);
        }

        // Borrar registros asociados de fotos y el reportaje
        $del_fotos = $pdo->prepare("DELETE FROM reportajes_fotos WHERE reportaje_id = :id");
        $del_fotos->execute([':id' => $id]);

        $del_rep = $pdo->prepare("DELETE FROM reportajes WHERE id = :id");
        $del_rep->execute([':id' => $id]);
    }
}

header('Location: index.php?msg=eliminado');
exit;