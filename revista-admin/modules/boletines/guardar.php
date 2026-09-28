<?php
require_once __DIR__ . '/../../includes/auth.php'; exigir_admin();
require_once '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $numero_boletin    = trim($_POST['numero_boletin']);
    $fecha_publicacion = $_POST['fecha_publicacion'];
    $resumen           = trim($_POST['resumen']);
    $usuario_id        = 1;

    $foto_portada = null;
    $archivo_pdf  = null;

    // Foto de Portada
    if (isset($_FILES['foto_portada']) && $_FILES['foto_portada']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['foto_portada']['name'], PATHINFO_EXTENSION);
        $foto_portada = 'boletin_portada_' . time() . '.' . strtolower($ext);
        move_uploaded_file($_FILES['foto_portada']['tmp_name'], '../../uploads/fotos/' . $foto_portada);
    }

    // PDF obligatorio
    if (isset($_FILES['archivo_pdf']) && $_FILES['archivo_pdf']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['archivo_pdf']['name'], PATHINFO_EXTENSION);
        $archivo_pdf = 'boletin_doc_' . time() . '.' . strtolower($ext);
        move_uploaded_file($_FILES['archivo_pdf']['tmp_name'], '../../uploads/pdfs/' . $archivo_pdf);
    }

    $sql = "INSERT INTO boletines (numero_boletin, resumen, foto_portada, archivo_pdf, fecha_publicacion, usuario_id) 
            VALUES (:numero_boletin, :resumen, :foto_portada, :archivo_pdf, :fecha_publicacion, :usuario_id)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':numero_boletin'    => $numero_boletin,
        ':resumen'           => $resumen,
        ':foto_portada'      => $foto_portada,
        ':archivo_pdf'       => $archivo_pdf,
        ':fecha_publicacion' => $fecha_publicacion,
        ':usuario_id'        => $usuario_id
    ]);

    header('Location: index.php?msg=creado');
    exit;
}