<?php
require_once __DIR__ . '/../../includes/auth.php'; exigir_admin();
require_once '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $tipo              = $_POST['tipo']; // 'podcast' o 'video'
    $titulo            = trim($_POST['titulo']);
    $url_embed         = trim($_POST['url_embed']);
    $fecha_publicacion = $_POST['fecha_publicacion'];
    $usuario_id        = 1;

    // Seleccionamos la tabla correcta según el tipo seleccionado
    $tabla = ($tipo === 'podcast') ? 'podcasts' : 'videos';

    $sql = "INSERT INTO {$tabla} (titulo, url_embed, fecha_publicacion, usuario_id) 
            VALUES (:titulo, :url_embed, :fecha_publicacion, :usuario_id)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':titulo'            => $titulo,
        ':url_embed'         => $url_embed,
        ':fecha_publicacion' => $fecha_publicacion,
        ':usuario_id'        => $usuario_id
    ]);

    header('Location: index.php?msg=creado');
    exit;
}