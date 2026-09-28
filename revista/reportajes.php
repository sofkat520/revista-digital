<?php
require_once 'config/database.php';

// ---- Paginación: 12 reportajes por página (4 filas de 3) ----
$por_pagina = 12;
$pagina     = max(1, (int) ($_GET['p'] ?? 1));

// Filtro opcional por mes (?mes=2026-08), pensado para el archivo mensual
$mes    = preg_match('/^\d{4}-\d{2}$/', $_GET['mes'] ?? '') ? $_GET['mes'] : '';
$where  = $mes !== '' ? "WHERE DATE_FORMAT(r.fecha_publicacion, '%Y-%m') = :mes" : '';
$params = $mes !== '' ? [':mes' => $mes] : [];

$st = $pdo->prepare("SELECT COUNT(*) FROM reportajes r $where");
$st->execute($params);
$total   = (int) $st->fetchColumn();
$paginas = max(1, (int) ceil($total / $por_pagina));
$pagina  = min($pagina, $paginas);
$offset  = ($pagina - 1) * $por_pagina;

$st = $pdo->prepare("
    SELECT r.id, r.titulo, r.foto_principal, r.fecha_publicacion
    FROM reportajes r
    $where
    ORDER BY r.fecha_publicacion DESC, r.id DESC
    LIMIT $por_pagina OFFSET $offset
");
$st->execute($params);
$reportajes = $st->fetchAll();

$qs = fn($p) => '?' . http_build_query(array_filter(['mes' => $mes, 'p' => $p > 1 ? $p : null]));

$titulo_pagina = "Todos los reportajes | Diálogo y Desarrollo";
include_once 'includes/header.php';
?>

<div class="container my-5">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <a href="index.php" class="text-danger text-decoration-none fw-bold">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
        <span class="text-muted small">
            <?= $total ?> reportaje<?= $total === 1 ? '' : 's' ?><?= $mes !== '' ? ' · ' . htmlspecialchars($mes) : '' ?>
        </span>
    </div>

    <?php if (!$reportajes): ?>
        <p class="text-center text-muted py-5">Aún no hay reportajes publicados.</p>
    <?php endif; ?>

    <!-- 1 columna en celular, 2 en tablet, 3 en escritorio -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-5">
        <?php foreach ($reportajes as $item): ?>
            <div class="col">
                <div class="card card-dyd h-100">
                    <a href="reportaje.php?id=<?= (int) $item['id'] ?>">
                        <img src="../revista-admin/uploads/fotos/<?= !empty($item['foto_principal']) ? rawurlencode($item['foto_principal']) : 'default.jpg' ?>"
                             class="card-img-top"
                             alt="<?= htmlspecialchars($item['titulo']) ?>"
                             loading="lazy"
                             onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=600&q=80';">
                    </a>
                    <div class="card-body d-flex flex-column">
                        <div class="card-date"><?= date('M d, Y', strtotime($item['fecha_publicacion'])) ?></div>
                        <h5 class="card-title flex-grow-1">
                            <a href="reportaje.php?id=<?= (int) $item['id'] ?>"><?= htmlspecialchars($item['titulo']) ?></a>
                        </h5>
                        <div class="mt-auto pt-3">
                            <a href="reportaje.php?id=<?= (int) $item['id'] ?>" class="link-readmore">
                                Leer <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if ($paginas > 1): ?>
        <nav aria-label="Paginación de reportajes">
            <ul class="pagination justify-content-center pagination-dyd">
                <li class="page-item <?= $pagina <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="<?= $qs($pagina - 1) ?>">&laquo;</a>
                </li>
                <?php for ($i = 1; $i <= $paginas; $i++): ?>
                    <li class="page-item <?= $i === $pagina ? 'active' : '' ?>">
                        <a class="page-link" href="<?= $qs($i) ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= $pagina >= $paginas ? 'disabled' : '' ?>">
                    <a class="page-link" href="<?= $qs($pagina + 1) ?>">&raquo;</a>
                </li>
            </ul>
        </nav>
    <?php endif; ?>

</div>

<?php include_once 'includes/footer.php'; ?>
