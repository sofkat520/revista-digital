<?php
require_once __DIR__ . '/../../includes/auth.php'; exigir_sesion();
require_once '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $titulo            = trim($_POST['titulo']);
    $autor_id          = (int)$_POST['autor_id'];
    $fecha_publicacion = $_POST['fecha_publicacion'];
    $resumen_corto     = trim($_POST['resumen_corto']);
    $desarrollo        = trim($_POST['desarrollo']);
    $es_destacado      = (es_admin() && isset($_POST['es_destacado'])) ? 1 : 0;
    
    // Asignamos estáticamente ID = 1 para el usuario activo (creado en la BD anteriormente)
    $usuario_id = usuario_id_actual();

    $foto_nombre = null;
    $pdf_nombre  = null;

    // Procesar la foto principal
    if (isset($_FILES['foto_principal']) && $_FILES['foto_principal']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['foto_principal']['name'], PATHINFO_EXTENSION);
        $foto_nombre = 'reportaje_img_' . time() . '_' . rand(100, 999) . '.' . strtolower($ext);
        $destino_foto = '../../uploads/fotos/' . $foto_nombre;
        
        move_uploaded_file($_FILES['foto_principal']['tmp_name'], $destino_foto);
    }

    // Procesar el PDF adjunto
    if (isset($_FILES['pdf_adjunto']) && $_FILES['pdf_adjunto']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['pdf_adjunto']['name'], PATHINFO_EXTENSION);
        $pdf_nombre = 'reportaje_pdf_' . time() . '_' . rand(100, 999) . '.' . strtolower($ext);
        $destino_pdf = '../../uploads/pdfs/' . $pdf_nombre;
        
        move_uploaded_file($_FILES['pdf_adjunto']['tmp_name'], $destino_pdf);
    }

    // Insertar en la BD
    $sql = "INSERT INTO reportajes 
            (titulo, resumen_corto, desarrollo, foto_principal, pdf_adjunto, fecha_publicacion, es_destacado, autor_id, usuario_id) 
            VALUES (:titulo, :resumen_corto, :desarrollo, :foto_principal, :pdf_adjunto, :fecha_publicacion, :es_destacado, :autor_id, :usuario_id)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':titulo'            => $titulo,
        ':resumen_corto'     => $resumen_corto,
        ':desarrollo'        => $desarrollo,
        ':foto_principal'    => $foto_nombre,
        ':pdf_adjunto'       => $pdf_nombre,
        ':fecha_publicacion' => $fecha_publicacion,
        ':es_destacado'      => $es_destacado,
        ':autor_id'          => $autor_id,
        ':usuario_id'        => $usuario_id
    ]);

    // Redireccionar al listado con mensaje de éxito
    header('Location: index.php?msg=creado');
    exit;
}