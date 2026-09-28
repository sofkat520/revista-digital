<?php
/**
 * Compresión y optimización automática de imágenes con GD Library.
 *
 * Regla de negocio: toda imagen subida se reescala a un máximo de 1200 px de
 * ancho y se reescribe como JPEG con calidad 80. Los PNG con transparencia
 * conservan su formato; el resto se normaliza a JPEG para ahorrar peso.
 */

define('IMG_ANCHO_MAX', 1200);
define('IMG_CALIDAD', 80);
define('IMG_PESO_MAX_BYTES', 8 * 1024 * 1024); // 8 MB de entrada

/**
 * Procesa un archivo de $_FILES: valida, comprime y guarda en $carpetaDestino.
 *
 * @param array  $archivo        Elemento de $_FILES.
 * @param string $carpetaDestino Ruta física con barra final.
 * @param string $prefijo        Prefijo del nombre generado.
 * @return array ['ok'=>bool, 'archivo'=>string, 'error'=>string]
 */
function procesar_imagen(array $archivo, string $carpetaDestino, string $prefijo = 'img'): array
{
    $fallo = fn(string $msg) => ['ok' => false, 'archivo' => '', 'error' => $msg];

    if (!isset($archivo['error']) || $archivo['error'] === UPLOAD_ERR_NO_FILE) {
        return $fallo('No se recibió ningún archivo.');
    }
    if ($archivo['error'] !== UPLOAD_ERR_OK) {
        return $fallo('La subida falló (código ' . $archivo['error'] . '). Revisa upload_max_filesize.');
    }
    if ($archivo['size'] > IMG_PESO_MAX_BYTES) {
        return $fallo('La imagen supera los 8 MB permitidos.');
    }
    if (!is_uploaded_file($archivo['tmp_name'])) {
        return $fallo('Origen del archivo no válido.');
    }

    $info = @getimagesize($archivo['tmp_name']);
    if ($info === false) {
        return $fallo('El archivo no es una imagen válida.');
    }

    [$ancho, $alto] = $info;
    $mime = $info['mime'];

    switch ($mime) {
        case 'image/jpeg': $origen = @imagecreatefromjpeg($archivo['tmp_name']); break;
        case 'image/png':  $origen = @imagecreatefrompng($archivo['tmp_name']);  break;
        case 'image/gif':  $origen = @imagecreatefromgif($archivo['tmp_name']);  break;
        case 'image/webp':
            $origen = function_exists('imagecreatefromwebp')
                ? @imagecreatefromwebp($archivo['tmp_name']) : false;
            break;
        default:
            return $fallo('Formato no admitido. Usa JPG, PNG, GIF o WebP.');
    }

    if (!$origen) {
        return $fallo('No se pudo leer la imagen. ¿Está habilitada la extensión GD?');
    }

    // ¿Hace falta redimensionar?
    if ($ancho > IMG_ANCHO_MAX) {
        $nuevoAncho = IMG_ANCHO_MAX;
        $nuevoAlto  = (int) round($alto * (IMG_ANCHO_MAX / $ancho));
    } else {
        $nuevoAncho = $ancho;
        $nuevoAlto  = $alto;
    }

    $lienzo = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

    $conservarPng = ($mime === 'image/png' || $mime === 'image/gif');
    if ($conservarPng) {
        imagealphablending($lienzo, false);
        imagesavealpha($lienzo, true);
        $transparente = imagecolorallocatealpha($lienzo, 0, 0, 0, 127);
        imagefilledrectangle($lienzo, 0, 0, $nuevoAncho, $nuevoAlto, $transparente);
    } else {
        $blanco = imagecolorallocate($lienzo, 255, 255, 255);
        imagefilledrectangle($lienzo, 0, 0, $nuevoAncho, $nuevoAlto, $blanco);
    }

    imagecopyresampled($lienzo, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
    imagedestroy($origen);

    if (!is_dir($carpetaDestino) && !mkdir($carpetaDestino, 0775, true)) {
        imagedestroy($lienzo);
        return $fallo('No se pudo crear la carpeta de destino.');
    }

    $extension = $conservarPng ? 'png' : 'jpg';
    $nombre    = $prefijo . '_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $extension;
    $ruta      = rtrim($carpetaDestino, '/') . '/' . $nombre;

    $guardado = $conservarPng
        ? imagepng($lienzo, $ruta, 6)
        : imagejpeg($lienzo, $ruta, IMG_CALIDAD);

    imagedestroy($lienzo);

    if (!$guardado) {
        return $fallo('No se pudo escribir la imagen en el servidor. Revisa permisos de uploads/.');
    }

    @chmod($ruta, 0644);
    return ['ok' => true, 'archivo' => $nombre, 'error' => ''];
}

/**
 * Guarda un PDF adjunto validando tipo real y peso.
 */
function procesar_pdf(array $archivo, string $carpetaDestino, string $prefijo = 'doc'): array
{
    $fallo = fn(string $msg) => ['ok' => false, 'archivo' => '', 'error' => $msg];

    if (!isset($archivo['error']) || $archivo['error'] === UPLOAD_ERR_NO_FILE) {
        return $fallo('No se recibió ningún archivo.');
    }
    if ($archivo['error'] !== UPLOAD_ERR_OK) {
        return $fallo('La subida del PDF falló.');
    }
    if ($archivo['size'] > 20 * 1024 * 1024) {
        return $fallo('El PDF supera los 20 MB permitidos.');
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    if ($finfo->file($archivo['tmp_name']) !== 'application/pdf') {
        return $fallo('El archivo adjunto debe ser un PDF.');
    }

    if (!is_dir($carpetaDestino) && !mkdir($carpetaDestino, 0775, true)) {
        return $fallo('No se pudo crear la carpeta de destino.');
    }

    $nombre = $prefijo . '_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.pdf';
    $ruta   = rtrim($carpetaDestino, '/') . '/' . $nombre;

    if (!move_uploaded_file($archivo['tmp_name'], $ruta)) {
        return $fallo('No se pudo mover el PDF al servidor.');
    }

    @chmod($ruta, 0644);
    return ['ok' => true, 'archivo' => $nombre, 'error' => ''];
}

/** Borra un archivo dentro de uploads/ de forma segura. */
function borrar_archivo_subido(?string $nombre, string $subcarpeta): void
{
    if (empty($nombre)) {
        return;
    }
    $nombre = basename($nombre);
    $ruta   = UPLOADS_PATH . '/' . trim($subcarpeta, '/') . '/' . $nombre;
    if (is_file($ruta)) {
        @unlink($ruta);
    }
}
