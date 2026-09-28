<?php
require_once 'config/database.php';

// 1. Reportaje Destacado Principal (1 solo)
$stmt_destacado = $pdo->query("
    SELECT r.*, CONCAT(a.nombres, ' ', IFNULL(a.ap_paterno, '')) AS autor_nombre 
    FROM reportajes r 
    INNER JOIN autores a ON r.autor_id = a.id 
    WHERE r.es_destacado = 1 
    ORDER BY r.fecha_publicacion DESC LIMIT 1
");
$destacado = $stmt_destacado->fetch();

// 2. Exactamente 3 Reportajes para la fila inferior
$where_not = $destacado ? "WHERE r.id != {$destacado['id']}" : "";
$stmt_reportajes = $pdo->query("
    SELECT r.*, CONCAT(a.nombres, ' ', IFNULL(a.ap_paterno, '')) AS autor_nombre 
    FROM reportajes r 
    INNER JOIN autores a ON r.autor_id = a.id 
    $where_not 
    ORDER BY r.fecha_publicacion DESC 
    LIMIT 3
");
$reportajes = $stmt_reportajes->fetchAll();

$titulo_pagina = "Reportajes | Diálogo y Desarrollo";
include_once 'includes/header.php';
?>

<div class="container my-5">

<!-- REPORTAJE DESTACADO -->
    <?php if ($destacado): ?>
        <div class="row align-items-center mb-5 pb-4">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="featured-img-container">
                    <img src="../revista-admin/uploads/fotos/<?= !empty($destacado['foto_principal']) ? htmlspecialchars($destacado['foto_principal']) : 'repo.jpg' ?>" 
                         class="featured-img" 
                         alt="<?= htmlspecialchars($destacado['titulo']) ?>"
                         onerror="this.onerror=null; this.src='../revista-admin/uploads/fotos/repo.jpg';">
                </div>
            </div>
            <div class="col-lg-6 ps-lg-4">
                <div class="featured-date">
                    <?= date('M d, Y', strtotime($destacado['fecha_publicacion'])) ?>
                </div>
                <a href="reportaje.php?id=<?= $destacado['id'] ?>" class="featured-title">
                    <?= htmlspecialchars($destacado['titulo']) ?>
                </a>
                <p class="featured-excerpt">
                    <?= htmlspecialchars($destacado['resumen_corto'] ?? substr(strip_tags($destacado['desarrollo']), 0, 180)) ?>...
                </p>
                <a href="reportaje.php?id=<?= $destacado['id'] ?>" class="link-readmore">
                    Leer <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    <?php endif; ?>

    <!-- FILA DE EXACTAMENTE 3 REPORTAJES -->
    <div class="row g-4 mb-5">
        <?php foreach ($reportajes as $item): ?>
            <div class="col-md-4">
                <div class="card card-dyd h-100">
                    <img src="../revista-admin/uploads/fotos/<?= !empty($item['foto_principal']) ? htmlspecialchars($item['foto_principal']) : 'default.jpg' ?>" 
                         class="card-img-top" 
                         alt="<?= htmlspecialchars($item['titulo']) ?>"
                         onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=600&q=80';">
                    <div class="card-body d-flex flex-column">
                        <div class="card-date">
                            <?= date('M d, Y', strtotime($item['fecha_publicacion'])) ?>
                        </div>
                        <h5 class="card-title flex-grow-1">
                            <a href="reportaje.php?id=<?= $item['id'] ?>">
                                <?= htmlspecialchars($item['titulo']) ?>
                            </a>
                        </h5>
                        <div class="mt-auto pt-3">
                            <a href="reportaje.php?id=<?= $item['id'] ?>" class="link-readmore">
                                Leer <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- BOTÓN VER TODOS DEBAJO DE LA FILA DE 3 -->
    <div class="text-center my-4">
        <a href="reportajes.php" class="btn-ver-todos">Ver todos</a>
    </div>

</div>

<?php include_once 'includes/footer.php'; ?>