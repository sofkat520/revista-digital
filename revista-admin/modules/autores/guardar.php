<?php
require_once __DIR__ . '/../../includes/auth.php'; exigir_admin();
require_once '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $nombres     = trim($_POST['nombres']);
    $ap_paterno  = trim($_POST['ap_paterno']);
    $ap_materno  = trim($_POST['ap_materno']);
    $nickname    = trim($_POST['nickname']);
    $es_nickname = isset($_POST['es_nickname']) ? 1 : 0;

    $sql = "INSERT INTO autores (nombres, ap_paterno, ap_materno, nickname, es_nickname) 
            VALUES (:nombres, :ap_paterno, :ap_materno, :nickname, :es_nickname)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nombres'     => $nombres,
        ':ap_paterno'  => $ap_paterno,
        ':ap_materno'  => $ap_materno,
        ':nickname'    => $nickname,
        ':es_nickname' => $es_nickname
    ]);

    header('Location: index.php?msg=creado');
    exit;
}