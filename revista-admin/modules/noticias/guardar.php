<?php
require_once __DIR__ . '/../../includes/auth.php'; exigir_admin();
require_once '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $titulo            = trim($_POST['titulo']);
    $fecha_publicacion = $_POST['fecha_publicacion'];
    $link_externo      = trim($_POST['link_externo']);
    $usuario_id        = 1;

    $foto_nombre = null;

    // Procesar Foto
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $foto_nombre = 'noticia_' . time() . '.' . strtolower($ext);
        move_uploaded_file($_FILES['foto']['tmp_name'], '../../uploads/fotos/' . $foto_nombre);
    }

    $sql = "INSERT INTO noticias (titulo, foto, link_externo, fecha_publicacion, usuario_id) 
            VALUES (:titulo, :foto, :link_externo, :fecha_publicacion, :usuario_id)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':titulo'            => $titulo,
        ':foto'              => $foto_nombre,
        ':link_externo'      => $link_externo ?: null,
        ':fecha_publicacion' => $fecha_publicacion,
        ':usuario_id'        => $usuario_id
    ]);

    header('Location: index.php?msg=creado');
    exit;
}