<?php
require_once __DIR__ . '/../../includes/auth.php'; exigir_sesion();
require_once '../../includes/auth.php';
require_once '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $reportaje_id = $_POST['reportaje_id'] ?? null;
    $pie_foto     = trim($_POST['pie_foto'] ?? '');

    if (!$reportaje_id) {
        die("Error: No se recibió el ID del reportaje.");
    }
    exigir_dueno_reportaje($pdo, $reportaje_id);

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        
        $directorio_destino = '../../uploads/fotos/';

        // Crear directorio si no existe
        if (!file_exists($directorio_destino)) {
            mkdir($directorio_destino, 0777, true);
        }

        $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $extensiones_permitidas = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

        if (!in_array($ext, $extensiones_permitidas)) {
            die("Error: Tipo de archivo no permitido. Solo se aceptan JPG, PNG, WEBP o GIF.");
        }

        $foto_nombre = 'galeria_' . time() . '_' . rand(100, 999) . '.' . $ext;
        $ruta_final  = $directorio_destino . $foto_nombre;

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $ruta_final)) {
            
            try {
                // Insertar en la base de datos
                $sql = "INSERT INTO reportajes_fotos (reportaje_id, url_foto, descripcion) VALUES (:reportaje_id, :foto, :pie_foto)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':reportaje_id' => $reportaje_id,
                    ':foto'         => $foto_nombre,
                    ':pie_foto'     => $pie_foto !== '' ? $pie_foto : null
                ]);

                header('Location: editar.php?id=' . $reportaje_id);
                exit;

            } catch (PDOException $e) {
                die("Error en la Base de Datos: " . $e->getMessage());
            }

        } else {
            die("Error al mover el archivo a la carpeta uploads/fotos/. Verifique permisos.");
        }

    } else {
        $error_code = $_FILES['foto']['error'] ?? 'desconocido';
        die("Error en la subida del archivo. Código de error PHP: " . $error_code);
    }
} else {
    header('Location: index.php');
    exit;
}