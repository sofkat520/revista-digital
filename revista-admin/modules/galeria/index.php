<?php
require_once __DIR__ . '/../../includes/auth.php'; exigir_admin();
$tituloPagina = 'Galería';
$moduloActivo = 'galeria';
require_once __DIR__ . '/../../includes/header.php';

$pagina    = max(1, (int) ($_GET['p'] ?? 1));
$porPagina = 24;
$desde     = ($pagina - 1) * $porPagina;

$total   = (int) $pdo->query('SELECT COUNT(*) FROM galeria')->fetchColumn();
$paginas = max(1, (int) ceil($total / $porPagina));

$fotos = $pdo->query(
    "SELECT id, titulo, archivo, fecha_subida FROM galeria
      ORDER BY fecha_subida DESC LIMIT $porPagina OFFSET $desde"
)->fetchAll();

/** URL absoluta desde la raíz del dominio, lista para pegar en CKEditor. */
$baseUrl = ADMIN_URL . '/uploads/galeria/';
?>

<header class="cabecera-seccion">
  <div>
    <h1>Galería</h1>
    <p>Sube una foto, copia su enlace y pégalo dentro de un reportaje.</p>
  </div>
</header>

<section class="panel mb-4">
  <form class="row g-2 align-items-end" method="post" action="subir.php" enctype="multipart/form-data">
    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
    <div class="col-md-5">
      <label class="form-label" for="archivo">Imagen</label>
      <input class="form-control" type="file" id="archivo" name="archivo"
             accept="image/jpeg,image/png,image/gif,image/webp" required
             data-previsualizar="#previaGaleria">
    </div>
    <div class="col-md-5">
      <label class="form-label" for="titulo">Título</label>
      <input class="form-control" id="titulo" name="titulo" maxlength="255"
             placeholder="Opcional, para reconocerla después">
    </div>
    <div class="col-md-2 d-grid">
      <button class="btn btn-primario">Subir foto</button>
    </div>
    <div class="col-12">
      <img class="vista-previa-foto mt-2 d-none" id="previaGaleria" src="" alt="">
      <p class="ayuda-campo mb-0 mt-2">Cada imagen se reduce a 1200 px de ancho y se comprime al 80 %.</p>
    </div>
  </form>
</section>

<?php if (!$fotos): ?>
  <div class="zona-vacia">
    <p class="mb-0">La galería está vacía. Sube la primera imagen con el formulario de arriba.</p>
  </div>
<?php else: ?>
  <p class="text-muted"><?= $total ?> <?= $total === 1 ? 'imagen' : 'imágenes' ?> guardadas.</p>

  <div class="rejilla-galeria">
    <?php foreach ($fotos as $f): ?>
      <?php $url = $baseUrl . rawurlencode($f['archivo']); ?>
      <figure class="tarjeta-foto m-0">
        <a href="<?= e($url) ?>" target="_blank" title="Abrir en tamaño completo">
          <img src="<?= e($url) ?>" alt="<?= e($f['titulo'] ?? '') ?>" loading="lazy">
        </a>
        <figcaption class="cuerpo">
          <span class="nombre"><?= e($f['titulo'] ?: $f['archivo']) ?></span>
          <span class="text-muted" style="font-size:.78rem">
            <?= date('d/m/Y', strtotime($f['fecha_subida'])) ?>
          </span>
          <div class="acciones">
            <button type="button" class="btn btn-sm btn-secundario flex-fill" data-copiar="<?= e($url) ?>">
              <i class="fa-regular fa-copy"></i> Copiar URL
            </button>
            <form method="post" action="eliminar.php" data-confirmar="¿Borrar esta imagen de la galería?">
              <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
              <input type="hidden" name="id" value="<?= (int) $f['id'] ?>">
              <button class="btn btn-sm btn-outline-danger" title="Eliminar">
                <i class="fa-regular fa-trash-can"></i>
              </button>
            </form>
          </div>
        </figcaption>
      </figure>
    <?php endforeach; ?>
  </div>

  <?php if ($paginas > 1): ?>
    <nav class="mt-4" aria-label="Paginación">
      <ul class="pagination">
        <?php for ($i = 1; $i <= $paginas; $i++): ?>
          <li class="page-item <?= $i === $pagina ? 'active' : '' ?>">
            <a class="page-link" href="?p=<?= $i ?>"><?= $i ?></a>
          </li>
        <?php endfor; ?>
      </ul>
    </nav>
  <?php endif; ?>
<?php endif; ?>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
