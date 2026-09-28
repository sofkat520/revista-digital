<?php
require_once 'config/database.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: index.php');
    exit;
}

// 1. Obtener el reportaje actual
$stmt = $pdo->prepare("
    SELECT r.*, CONCAT(a.nombres, ' ', IFNULL(a.ap_paterno, '')) AS autor_nombre 
    FROM reportajes r 
    INNER JOIN autores a ON r.autor_id = a.id 
    WHERE r.id = :id
");
$stmt->execute([':id' => $id]);
$reportaje = $stmt->fetch();

if (!$reportaje) {
    header('Location: index.php');
    exit;
}

// 2. Sidebar: Últimas noticias / reportajes (excluyendo el actual)
$stmt_ultimas = $pdo->prepare("
    SELECT id, titulo, fecha_publicacion 
    FROM reportajes 
    WHERE id != :id 
    ORDER BY fecha_publicacion DESC 
    LIMIT 3
");
$stmt_ultimas->execute([':id' => $id]);
$ultimas_noticias = $stmt_ultimas->fetchAll();

// 3. Sidebar: Archivos dinámicos por mes
$archivos = $pdo->query("
    SELECT DISTINCT DATE_FORMAT(fecha_publicacion, '%Y-%m') as mes_val, 
           DATE_FORMAT(fecha_publicacion, '%M %Y') as mes_nombre 
    FROM reportajes 
    ORDER BY fecha_publicacion DESC
")->fetchAll();

$titulo_pagina = htmlspecialchars($reportaje['titulo']) . " | DDP Noticias";
include_once 'includes/header.php';
?>

<!-- BREADCRUMB EN LA BARRA SUPERIOR -->
<div class="bg-light py-2 border-bottom">
    <div class="container d-flex justify-content-between align-items-center">
        <h2 class="h5 m-0 fw-bold text-secondary">Reportajes</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb m-0 small">
                <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-muted">Inicio</a></li>
                <li class="breadcrumb-item active text-danger" aria-current="page">Reportajes</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container my-5">
    <div class="row g-5">
        
        <!-- COLUMNA IZQUIERDA: DETALLE DEL ARTÍCULO -->
        <div class="col-lg-8">
            
            <h1 class="display-6 fw-bold text-dark mb-4" style="line-height: 1.3;">
                <?= htmlspecialchars($reportaje['titulo']) ?>
            </h1>

            <?php if (!empty($reportaje['foto_principal'])): ?>
                <div class="mb-3 text-center">
                    <a href="../revista-admin/uploads/fotos/<?= htmlspecialchars($reportaje['foto_principal']) ?>" target="_blank" class="d-block">
                        <img src="../revista-admin/uploads/fotos/<?= htmlspecialchars($reportaje['foto_principal']) ?>" 
                             class="img-fluid rounded" 
                             alt="<?= htmlspecialchars($reportaje['titulo']) ?>"
                             style="width: 100%; max-height: 480px; object-fit: cover;"
                             onerror="this.onerror=null; this.src='../revista-admin/uploads/fotos/repo.jpg';">
                    </a>
                    <small class="text-danger d-block mt-2 fw-semibold" style="font-size: 0.85rem;">
                        Clic en la imagen para ver la infografía completa
                    </small>
                </div>
            <?php endif; ?>

            <?php if (!empty($reportaje['resumen_corto'])): ?>
                <blockquote class="fst-italic text-secondary my-4 fs-5 px-3 border-start border-0">
                    “<?= htmlspecialchars($reportaje['resumen_corto']) ?>”
                </blockquote>
            <?php endif; ?>

            <div class="article-content fs-6 text-secondary lh-lg mb-5">
                <?= nl2br($reportaje['desarrollo']) ?>
            </div>

            <?php if (!empty($reportaje['pdf_adjunto'])): ?>
                <div class="p-3 bg-light border-start border-4 border-danger rounded d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <strong class="d-block text-dark"><i class="fas fa-file-pdf text-danger me-2"></i> Documento Oficial Adjunto</strong>
                        <small class="text-muted">Descarga el informe completo en formato PDF</small>
                    </div>
                    <a href="../revista-admin/uploads/pdf/<?= htmlspecialchars($reportaje['pdf_adjunto']) ?>" target="_blank" class="btn btn-sm btn-danger fw-bold">
                        Descargar PDF
                    </a>
                </div>
            <?php endif; ?>

            <div class="pt-4 border-top">
                <a href="index.php" class="text-danger text-decoration-none fw-bold">
                    <i class="fas fa-arrow-left me-1"></i> Volver a Reportajes
                </a>
            </div>

        </div>

        <!-- COLUMNA DERECHA: SIDEBAR -->
        <div class="col-lg-4">
            
            <!-- Bloque Últimas noticias -->
            <div class="mb-5">
                <h4 class="fw-bold text-dark fs-5 mb-4">Últimas noticias</h4>
                <div class="d-flex flex-column gap-3">
                    <?php foreach ($ultimas_noticias as $item): ?>
                        <div class="border-bottom pb-3">
                            <a href="reportaje.php?id=<?= $item['id'] ?>" class="text-dark text-decoration-none fw-semibold d-block mb-1" style="font-size: 0.95rem; line-height: 1.35;">
                                <?= htmlspecialchars($item['titulo']) ?>
                            </a>
                            <small class="text-muted" style="font-size: 0.8rem;">
                                <?= date('M d, Y', strtotime($item['fecha_publicacion'])) ?>
                            </small>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Bloque Archivos -->
            <div>
                <h4 class="fw-bold text-dark fs-5 mb-3">Archivos</h4>
                <ul class="list-unstyled text-secondary small lh-lg">
                    <li class="mb-1"><a href="#" class="text-muted text-decoration-none">Agosto 2026</a></li>
                    <li class="mb-1"><a href="#" class="text-muted text-decoration-none">Julio 2026</a></li>
                    <li class="mb-1"><a href="#" class="text-muted text-decoration-none">Junio 2026</a></li>
                    <li class="mb-1"><a href="#" class="text-muted text-decoration-none">Mayo 2026</a></li>
                    <li class="mb-1"><a href="#" class="text-muted text-decoration-none">Abril 2026</a></li>
                    <li class="mb-1"><a href="#" class="text-muted text-decoration-none">Marzo 2026</a></li>
                </ul>
            </div>

        </div>

    </div>
</div>

<?php include_once 'includes/footer.php'; ?>