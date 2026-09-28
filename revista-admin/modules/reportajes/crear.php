<?php
require_once __DIR__ . '/../../includes/auth.php'; exigir_sesion();
require_once '../../includes/auth.php';
require_once '../../config/database.php';

// Obtener autores
$autores = $pdo->query("SELECT id, CONCAT(nombres, ' ', IFNULL(ap_paterno, '')) AS nombre FROM autores ORDER BY nombres ASC")->fetchAll();

// Control de errores por si no existe la tabla galeria
try {
    $fotos_galeria = $pdo->query("SELECT * FROM galeria ORDER BY fecha_subida DESC")->fetchAll();
} catch (Exception $e) {
    $fotos_galeria = [];
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo          = trim($_POST['titulo']);
    $resumen_corto   = trim($_POST['resumen_corto']);
    $desarrollo      = $_POST['desarrollo']; // Formato HTML Summernote
    $fecha_pub       = !empty($_POST['fecha_publicacion']) ? $_POST['fecha_publicacion'] : date('Y-m-d H:i:s');
    $es_destacado    = (es_admin() && isset($_POST['es_destacado'])) ? 1 : 0; // solo admin
    
    // Gestión del Autor
    $autor_id = $_POST['autor_id'] ?? null;
    if ($autor_id === 'nuevo') {
        $nuevo_nombre = trim($_POST['nuevo_autor_nombre']);
        $nuevo_apellido = trim($_POST['nuevo_autor_apellido']);
        if (!empty($nuevo_nombre)) {
            $stmt_aut = $pdo->prepare("INSERT INTO autores (nombres, ap_paterno) VALUES (:nom, :ape)");
            $stmt_aut->execute([':nom' => $nuevo_nombre, ':ape' => $nuevo_apellido]);
            $autor_id = $pdo->lastInsertId();
        }
    }

    $descripcion_foto = trim($_POST['descripcion_foto'] ?? '');
    $origen_foto = $_POST['origen_foto'] ?? 'subir';

    if (empty($titulo) || empty($desarrollo) || empty($autor_id)) {
        $error = "El Título, Autor y Desarrollo son obligatorios.";
    } elseif ($origen_foto === 'subir' && isset($_FILES['foto_principal']) && $_FILES['foto_principal']['error'] === UPLOAD_ERR_OK && empty($descripcion_foto)) {
        $error = "Debes ingresar una descripción o pie de foto para la imagen subida.";
    } else {
        try {
            if ($es_destacado == 1) {
                $pdo->query("UPDATE reportajes SET es_destacado = 0");
            }

            // Subir Foto SIN LIBRERÍA GD (Soporta JPG, PNG, WEBP directamente)
            $foto_nombre = '';
            if ($origen_foto === 'galeria' && !empty($_POST['foto_galeria_seleccionada'])) {
                // La galería vive en uploads/galeria; el portal lee uploads/fotos, así que se copia
                $origen_g = '../../uploads/galeria/' . basename($_POST['foto_galeria_seleccionada']);
                if (is_file($origen_g)) {
                    $dir_f = '../../uploads/fotos/';
                    if (!is_dir($dir_f)) mkdir($dir_f, 0777, true);
                    $foto_nombre = 'rep_' . time() . '_' . rand(100, 999) . '.' . pathinfo($origen_g, PATHINFO_EXTENSION);
                    copy($origen_g, $dir_f . $foto_nombre);
                }
            } elseif ($origen_foto === 'subir' && isset($_FILES['foto_principal']) && $_FILES['foto_principal']['error'] === UPLOAD_ERR_OK) {
                $dir_fotos = '../../uploads/fotos/';
                if (!is_dir($dir_fotos)) mkdir($dir_fotos, 0777, true);

                $ext_foto = pathinfo($_FILES['foto_principal']['name'], PATHINFO_EXTENSION);
                $foto_nombre = 'rep_' . time() . '_' . rand(100, 999) . '.' . $ext_foto;
                move_uploaded_file($_FILES['foto_principal']['tmp_name'], $dir_fotos . $foto_nombre);
            }

            // PDF Adjunto
            $pdf_nombre = null;
            if (isset($_POST['incluir_pdf']) && isset($_FILES['pdf_adjunto']) && $_FILES['pdf_adjunto']['error'] === UPLOAD_ERR_OK) {
                $dir_multimedia = '../../uploads/pdf/'; // el portal busca los PDF aquí
                if (!is_dir($dir_multimedia)) mkdir($dir_multimedia, 0777, true);

                $ext_pdf = pathinfo($_FILES['pdf_adjunto']['name'], PATHINFO_EXTENSION);
                if (strtolower($ext_pdf) === 'pdf') {
                    $pdf_nombre = 'doc_' . time() . '.' . $ext_pdf;
                    move_uploaded_file($_FILES['pdf_adjunto']['tmp_name'], $dir_multimedia . $pdf_nombre);
                }
            }

            // Guardar Reportaje
            $stmt_rep = $pdo->prepare("
                INSERT INTO reportajes (titulo, resumen_corto, desarrollo, autor_id, usuario_id, es_destacado, foto_principal, pdf_adjunto, fecha_publicacion) 
                VALUES (:titulo, :resumen, :desarrollo, :autor_id, :usuario_id, :destacado, :foto, :pdf, :fecha)
            ");
            $stmt_rep->execute([
                ':titulo'    => $titulo,
                ':resumen'   => $resumen_corto,
                ':desarrollo'=> $desarrollo,
                ':autor_id'  => $autor_id,
                ':usuario_id'=> usuario_id_actual(),
                ':destacado' => $es_destacado,
                ':foto'      => $foto_nombre,
                ':pdf'       => $pdf_nombre,
                ':fecha'     => $fecha_pub
            ]);

            $reportaje_id = $pdo->lastInsertId();

            // Guardar en reportaje_fotos
            if (!empty($foto_nombre)) {
                $stmt_foto = $pdo->prepare("INSERT INTO reportajes_fotos (reportaje_id, url_foto, descripcion) VALUES (:rep_id, :archivo, :desc)");
                $stmt_foto->execute([':rep_id' => $reportaje_id, ':archivo' => $foto_nombre, ':desc' => $descripcion_foto]);
            }

            header('Location: index.php?msg=creado');
            exit;
        } catch (PDOException $e) {
            $error = "Error al guardar: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Crear Reportaje - D&D Admin</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/plugins/fontawesome/css/all.min.css">
    <link id="theme-style" rel="stylesheet" href="../../assets/css/portal.css">
    
    <!-- Editor Summernote CSS -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
</head> 

<body class="app">   
    <header class="app-header fixed-top">      
        <?php include_once '../../includes/sidebar.php'; ?>
    </header>
    
    <div class="app-wrapper">
        <div class="app-content pt-3 p-md-3 p-lg-4">
            <div class="container-xl">
                
                <div class="row g-3 mb-4 align-items-center justify-content-between">
                    <div class="col-auto">
                        <h1 class="app-page-title mb-0">Crear Reportaje</h1>
                    </div>
                    <div class="col-auto">
                        <a href="index.php" class="btn btn-secondary text-white">
                            <i class="fas fa-arrow-left me-1"></i> Volver a la Lista
                        </a>
                    </div>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-danger mb-4"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <div class="app-card shadow-sm p-4">
                    <form action="" method="POST" enctype="multipart/form-data">
                        
                        <div class="row mb-3">
                            <div class="col-md-8 mb-3 mb-md-0">
                                <label class="form-label fw-bold">Título del Reportaje *</label>
                                <input type="text" name="titulo" class="form-control" required placeholder="Título principal...">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Autor *</label>
                                <select name="autor_id" class="form-select" onchange="toggleNuevoAutor(this.value)" required>
                                    <option value="">-- Seleccionar Autor --</option>
                                    <?php foreach ($autores as $aut): ?>
                                        <option value="<?= $aut['id'] ?>"><?= htmlspecialchars($aut['nombre']) ?></option>
                                    <?php endforeach; ?>
                                    <option value="nuevo" class="fw-bold text-success">+ Crear nuevo autor...</option>
                                </select>
                            </div>
                        </div>

                        <div id="box_nuevo_autor" class="row mb-3 d-none p-3 bg-light border rounded ms-0 me-0">
                            <div class="col-md-6 mb-2 mb-md-0">
                                <input type="text" name="nuevo_autor_nombre" class="form-control" placeholder="Nombre(s) del nuevo autor">
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="nuevo_autor_apellido" class="form-control" placeholder="Apellido paterno">
                            </div>
                        </div>

                        <?php if (es_admin()): ?>
                        <div class="app-card p-3 mb-3 bg-light border-0">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="es_destacado" id="es_destacado" value="1">
                                <label class="form-check-label fw-bold text-danger me-2" for="es_destacado">
                                    <i class="fas fa-star me-1"></i> Marcar como Reportaje Destacado (Portada Principal)
                                </label>
                            </div>
                            <small class="text-muted ms-4">Si esta opción está activada, aparecerá arriba como la noticia principal de la revista.</small>
                        </div>
                        <?php endif; ?>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Resumen Corto (Bajada)</label>
                            <textarea name="resumen_corto" class="form-control" rows="2" placeholder="Resumen o bajada..."></textarea>
                        </div>

                        <!-- Editor Rich Text para Desarrollo -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Desarrollo / Contenido Completo *</label>
                            <textarea name="desarrollo" id="editor_desarrollo" class="form-control" required></textarea>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <div class="app-card p-3 bg-light border-0 h-100">
                                    <label class="form-label fw-bold"><i class="fas fa-image me-1"></i> Foto Principal</label>
                                    <div class="d-flex gap-3 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="origen_foto" id="opt_subir" value="subir" checked onclick="toggleOrigenFoto('subir')">
                                            <label class="form-check-label" for="opt_subir">Subir nuevo</label>
                                        </div>
                                        <?php if (!empty($fotos_galeria)): ?>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="origen_foto" id="opt_galeria" value="galeria" onclick="toggleOrigenFoto('galeria')">
                                            <label class="form-check-label" for="opt_galeria">Elegir de Galería</label>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <div id="box_subir_foto" class="mb-2">
                                        <input type="file" name="foto_principal" class="form-control" accept="image/*">
                                    </div>
                                    <?php if (!empty($fotos_galeria)): ?>
                                    <div id="box_galeria_foto" class="mb-2 d-none">
                                        <select name="foto_galeria_seleccionada" class="form-select">
                                            <option value="">-- Seleccionar imagen existente --</option>
                                            <?php foreach ($fotos_galeria as $fg): ?>
                                                <option value="<?= $fg['archivo'] ?>"><?= htmlspecialchars($fg['archivo']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <?php endif; ?>
                                    <input type="text" name="descripcion_foto" id="input_desc_foto" class="form-control mt-2" placeholder="Pie de foto / descripción..." required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="app-card p-3 bg-light border-0 h-100">
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" name="incluir_pdf" id="check_pdf" onchange="toggleBoxPdf(this.checked)">
                                        <label class="form-check-label fw-bold" for="check_pdf"><i class="fas fa-file-pdf me-1"></i> Adjuntar Documento PDF</label>
                                    </div>
                                    <div id="box_pdf" class="d-none mt-2">
                                        <input type="file" name="pdf_adjunto" class="form-control" accept=".pdf">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Fecha de Publicación</label>
                                <input type="datetime-local" name="fecha_publicacion" class="form-control" value="<?= date('Y-m-d\TH:i') ?>">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="index.php" class="btn btn-secondary text-white">Cancelar</a>
                            <button type="submit" class="btn btn-danger text-white fw-bold">
                                <i class="fas fa-save me-1"></i> Guardar Reportaje
                            </button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- Scripts de la plantilla y Summernote -->
    <script src="../../assets/plugins/popper.min.js"></script>
    <script src="../../assets/plugins/bootstrap/js/bootstrap.min.js"></script>  
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script src="../../assets/js/app.js"></script> 

    <script>
        $(document).ready(function() {
            $('#editor_desarrollo').summernote({
                placeholder: 'Escribe el contenido completo del reportaje aquí...',
                tabsize: 2,
                height: 300,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'picture', 'blockquote', 'table']],
                    ['view', ['fullscreen', 'codeview']]
                ]
            });
        });

        function toggleNuevoAutor(val) {
            document.getElementById('box_nuevo_autor').classList.toggle('d-none', val !== 'nuevo');
        }

        function toggleOrigenFoto(tipo) {
            const isSubir = tipo === 'subir';
            document.getElementById('box_subir_foto').classList.toggle('d-none', !isSubir);
            const boxGaleria = document.getElementById('box_galeria_foto');
            if (boxGaleria) boxGaleria.classList.toggle('d-none', isSubir);
            document.getElementById('input_desc_foto').required = isSubir;
        }

        function toggleBoxPdf(checked) {
            document.getElementById('box_pdf').classList.toggle('d-none', !checked);
        }
    </script>
</body>
</html>