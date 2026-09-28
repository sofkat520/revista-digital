<?php
/**
 * PLANTILLA de conexión (esta SÍ va en GitHub).
 *
 * Cómo usarla:
 *   1. Copia este archivo como  database.php  (en la misma carpeta).
 *   2. Reemplaza los valores por los de tu servidor.
 *   database.php está en .gitignore: las credenciales reales NUNCA se suben al repositorio.
 *
 * Local (XAMPP):     host 127.0.0.1 | usuario root | clave vacía | base revista_digital
 * Hosting (cPanel):  host localhost | usuario y base CON el prefijo de tu cuenta (ej. cuenta_revista)
 */
$host    = 'localhost';
$db      = 'revista_digital';     // en hosting: 'cuenta_revista'
$user    = 'root';                // en hosting: 'cuenta_usuario'
$pass    = '';                    // en hosting: contraseña fuerte del usuario MySQL
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    error_log('Conexión BD: ' . $e->getMessage());   // el detalle queda en el log del servidor
    http_response_code(500);
    die('No se pudo conectar con la base de datos. Revisa config/database.php.');
}
