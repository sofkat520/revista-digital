<?php
require_once __DIR__ . '/../../includes/auth.php'; exigir_sesion();
// Si usas otra variable de sesión (ej. $_SESSION['usuario']), la validamos sin forzar la redirección si ya estás en el panel:
if (!isset($_SESSION['admin_id']) && !isset($_SESSION['usuario']) && !isset($_SESSION['usuario_id'])) {
    header('Location: ../../login.php');
    exit;
}

require_once '../../config/database.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: index.php');
    exit;
}

// 1. Cargar datos del reportaje actual
$stmt = $pdo->prepare("SELECT * FROM reportajes WHERE id = :id");
$stmt->execute([':id' => $id]);
$reportaje = $stmt->fetch();

if (!$reportaje) {
    header('Location: index.php');
    exit;
}

// Un redactor solo puede editar sus propios reportajes
if (!puede_editar_reportaje($reportaje)) {
    flash('warning', 'Ese reportaje pertenece a otro usuario.');
    header('Location: index.php');
    exit;
}

// 2. Obtener lista de autores
$autores = $pdo->query("SELECT id, CONCAT(nombres, ' ', IFNULL(ap_paterno, '')) AS nombre FROM autores")->fetchAll();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo']);
    $resumen_corto = trim($_POST['resumen_corto']);
    $desarrollo = trim($_POST['desarrollo']);
    $autor_id = $_POST['autor_id'];
    $es_destacado = es_admin() ? (isset($_POST['es_destacado']) ? 1 : 0) : (int) $reportaje['es_destacado'];

    if (empty($titulo) || empty($desarrollo) || empty($autor_id)) {
        $error = "Los campos Título, Autor y Desarrollo son obligatorios.";
    } else {
        try {
            // Si este reportaje se marca como destacado, quitar la marca a los demás
            if (es_admin() && $es_destacado == 1) {
                $pdo->query("UPDATE reportajes SET es_destacado = 0");
            }

            // Imagen Principal
            $foto_nombre = $reportaje['foto_principal'];
            if (isset($_FILES['foto_principal']) && $_FILES['foto_principal']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['foto_principal']['name'], PATHINFO_EXTENSION);
                $foto_nombre = 'rep_' . time() . '.' . $ext;
                move_uploaded_file($_FILES['foto_principal']['tmp_name'], '../../uploads/fotos/' . $foto_nombre);
            }

            // Documento PDF
            $pdf_nombre = $reportaje['pdf_adjunto'];
            if (isset($_FILES['pdf_adjunto']) && $_FILES['pdf_adjunto']['error'] === UPLOAD_ERR_OK) {
                $ext_pdf = pathinfo($_FILES['pdf_adjunto']['name'], PATHINFO_EXTENSION);
                $pdf_nombre = 'pdf_' . time() . '.' . $ext_pdf;
                move_uploaded_file($_FILES['pdf_adjunto']['tmp_name'], '../../uploads/pdf/' . $pdf_nombre);
            }

            // Actualizar registro
            $stmt_update = $pdo->prepare("
                UPDATE reportajes SET 
                    titulo = :titulo,
                    resumen_corto = :resumen,
                    desarrollo = :desarrollo,
                    autor_id = :autor_id,
                    es_destacado = :destacado,
                    foto_principal = :foto,
                    pdf_adjunto = :pdf
                WHERE id = :id
            ");

            $stmt_update->execute([
                ':titulo'    => $titulo,
                ':resumen'   => $resumen_corto,
                ':desarrollo'=> $desarrollo,
                ':autor_id'  => $autor_id,
                ':destacado' => $es_destacado,
                ':foto'      => $foto_nombre,
                ':pdf'       => $pdf_nombre,
                ':id'        => $id
            ]);

            header('Location: index.php?msg=actualizado');
            exit;

        } catch (PDOException $e) {
            $error = "Error al actualizar: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Reportaje | Panel Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0"><i class="fas fa-edit me-2"></i> Editar Reportaje #<?= $reportaje['id'] ?></h5>
                    <a href="index.php" class="btn btn-sm btn-outline-light">Volver a la lista</a>
                </div>
                <div class="card-body p-4">

                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>

                    <form action="" method="POST" enctype="multipart/form-data">
                        
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="form-label fw-bold">Título del Reportaje *</label>
                                <input type="text" name="titulo" class="form-control" value="<?= htmlspecialchars($reportaje['titulo']) ?>" required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Autor *</label>
                                <select name="autor_id" class="form-select" required>
                                    <?php foreach ($autores as $autor): ?>
                                        <option value="<?= $autor['id'] ?>" <?= $autor['id'] == $reportaje['autor_id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($autor['nombre']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <?php if (es_admin()): ?>
                        <!-- CASILLA PARA MARCAR COMO DESTACADO EN LA WEB -->
                        <div class="form-check form-switch mb-4 p-3 bg-white border rounded">
                            <input class="form-check-input ms-0 me-2" type="checkbox" name="es_destacado" id="es_destacado" value="1" <?= $reportaje['es_destacado'] ? 'checked' : '' ?>>
                            <label class="form-check-label fw-bold text-danger" for="es_destacado">
                                <i class="fas fa-star me-1"></i> Marcar como Reportaje Destacado (Portada Principal)
                            </label>
                            <small class="d-block text-muted ms-4">Si esta opción está activada, aparecerá arriba como la noticia principal de la revista.</small>
                        </div>
                        <?php endif; ?>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Resumen Corto (Bajada)</label>
                            <textarea name="resumen_corto" class="form-control" rows="2"><?= htmlspecialchars($reportaje['resumen_corto'] ?? '') ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Desarrollo / Contenido Completo *</label>
                            <textarea name="desarrollo" class="form-control" rows="8" required><?= htmlspecialchars($reportaje['desarrollo']) ?></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Cambiar Foto Principal</label>
                                <input type="file" name="foto_principal" class="form-control" accept="image/*">
                                <?php if (!empty($reportaje['foto_principal'])): ?>
                                    <small class="text-muted d-block mt-1">Archivo actual: <?= htmlspecialchars($reportaje['foto_principal']) ?></small>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Cambiar Documento PDF</label>
                                <input type="file" name="pdf_adjunto" class="form-control" accept=".pdf">
                                <?php if (!empty($reportaje['pdf_adjunto'])): ?>
                                    <small class="text-muted d-block mt-1">Archivo actual: <?= htmlspecialchars($reportaje['pdf_adjunto']) ?></small>
                                <?php endif; ?>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-end gap-2">
                            <a href="index.php" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-danger fw-bold"><i class="fas fa-save me-1"></i> Actualizar Reportaje</button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>