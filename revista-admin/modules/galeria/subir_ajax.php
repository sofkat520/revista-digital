<?php
require_once __DIR__ . '/../../includes/auth.php'; exigir_sesion();
/**
 * Punto de subida para el editor: recibe la imagen que el redactor arrastra
 * dentro de CKEditor, la comprime, la registra en la galería y devuelve su URL.
 *
 * Respuesta esperada por CKEditor 5: {"url": "..."} o {"error": {"message": "..."}}
 */
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/imagen.php';

header('Content-Type: application/json; charset=utf-8');

$responder = function (array $carga, int $codigo = 200) {
    http_response_code($codigo);
    echo json_encode($carga, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
};

if (!sesion_activa()) {
    $responder(['error' => ['message' => 'Tu sesión caducó. Vuelve a entrar al panel.']], 401);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_valido($_POST['csrf'] ?? null)) {
    $responder(['error' => ['message' => 'Petición no válida.']], 400);
}

$res = procesar_imagen($_FILES['upload'] ?? [], UPLOADS_PATH . '/galeria', 'edi');

if (!$res['ok']) {
    $responder(['error' => ['message' => $res['error']]], 422);
}

try {
    $ins = $pdo->prepare('INSERT INTO galeria (titulo, archivo) VALUES (?,?)');
    $ins->execute([$_POST['titulo'] ?? null, $res['archivo']]);
} catch (PDOException $e) {
    borrar_archivo_subido($res['archivo'], 'galeria');
    $responder(['error' => ['message' => 'No se pudo registrar la imagen.']], 500);
}

$responder(['url' => ADMIN_URL . '/uploads/galeria/' . rawurlencode($res['archivo'])]);
