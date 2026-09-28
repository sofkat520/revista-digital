<?php
require_once __DIR__ . '/../../includes/auth.php'; exigir_admin();
require_once '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $nombres    = trim($_POST['nombres']);
    $ap_paterno = trim($_POST['ap_paterno']);
    $ap_materno = trim($_POST['ap_materno']);
    $email      = trim($_POST['email']);
    $rol        = in_array($_POST['rol'] ?? '', ['admin', 'redactor'], true) ? $_POST['rol'] : 'redactor';
    $password   = $_POST['password'];

    // Encriptación segura de contraseña
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    $sql = "INSERT INTO usuarios (nombres, ap_paterno, ap_materno, email, password_hash, rol) 
            VALUES (:nombres, :ap_paterno, :ap_materno, :email, :password_hash, :rol)";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nombres'       => $nombres,
            ':ap_paterno'    => $ap_paterno,
            ':ap_materno'    => $ap_materno,
            ':email'         => $email,
            ':password_hash' => $password_hash,
            ':rol'           => $rol
        ]);

        header('Location: index.php?msg=creado');
        exit;
    } catch (PDOException $e) {
        die("Error al registrar el usuario: " . $e->getMessage());
    }
}