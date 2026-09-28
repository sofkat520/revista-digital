<?php
/**
 * Formulario compartido por crear.php y editar.php.
 * Espera: $datos (array con los campos del reportaje), $autores, $esEdicion.
 */
$esEdicion = $esEdicion ?? false;
$datos = array_merge([
    'id' => 0, 'titulo' => '', 'resumen_corto' => '', 'desarrollo' => '',
    'autor_id' => 0, 'es_destacado' => 0, 'foto_principal' => '',
    'pdf_adjunto' => '', 'fecha_publicacion' => date('Y-m-d\TH:i'),
], $datos ?? []);
?>
<form method="post" action="guardar.php" enctype="multipart/form-data" id="formReportaje">
  <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
  <input type="hidden" name="id" value="<?= (int) $datos['id'] ?>">

  <div class="row g-4">
    <div class="col-lg-8">
      <div class="panel mb-3">
        <div class="mb-3">
          <label class="form-label" for="titulo">Título</label>
          <input class="form-control form-control-lg" id="titulo" name="titulo" maxlength="255"
                 required value="<?= e($datos['titulo']) ?>">
        </div>

        <div class="mb-3">
          <label class="form-label" for="resumen_corto">Bajada</label>
          <textarea class="form-control" id="resumen_corto" name="resumen_corto" rows="3"
                    placeholder="Dos o tres líneas que resuman la noticia."><?= e($datos['resumen_corto']) ?></textarea>
          <p class="ayuda-campo mb-0">Aparece bajo el título en la portada y en el detalle del reportaje.</p>
        </div>

        <div>
          <label class="form-label" for="desarrollo">Desarrollo</label>
          <textarea id="desarrollo" name="desarrollo"><?= e($datos['desarrollo']) ?></textarea>
          <p class="ayuda-campo mt-2 mb-0">
            Para insertar una foto, cópiala desde la
            <a href="<?= ADMIN_URL ?>/modules/galeria/index.php" target="_blank">galería</a>
            y pega la URL con el botón de imagen del editor.
          </p>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="panel mb-3">
        <h2 class="titulo-panel">Publicación</h2>

        <div class="mb-3">
          <label class="form-label" for="autor_id">Autor</label>
          <select class="form-select" id="autor_id" name="autor_id" required>
            <option value="">Elige un autor…</option>
            <?php foreach ($autores as $a): ?>
              <option value="<?= (int) $a['id'] ?>" <?= (int) $datos['autor_id'] === (int) $a['id'] ? 'selected' : '' ?>>
                <?= e(trim($a['nombres'] . ' ' . ($a['ap_paterno'] ?? ''))) ?>
              </option>
            <?php endforeach; ?>
          </select>
          <?php if (!$autores): ?>
            <p class="ayuda-campo mb-0 text-danger">
              No hay autores. <a href="<?= ADMIN_URL ?>/modules/autores/index.php">Crea uno primero</a>.
            </p>
          <?php endif; ?>
        </div>

        <div class="mb-3">
          <label class="form-label" for="fecha_publicacion">Fecha y hora</label>
          <input class="form-control" type="datetime-local" id="fecha_publicacion" name="fecha_publicacion"
                 value="<?= e(date('Y-m-d\TH:i', strtotime($datos['fecha_publicacion']))) ?>">
        </div>

        <div class="form-check form-switch">
          <input class="form-check-input" type="checkbox" role="switch"
                 id="es_destacado" name="es_destacado" value="1"
                 <?= (int) $datos['es_destacado'] === 1 ? 'checked' : '' ?>>
          <label class="form-check-label" for="es_destacado">Llevar a portada</label>
        </div>
        <p class="ayuda-campo mb-0">Solo un reportaje puede ocupar la portada: el que esté ahí se retirará.</p>
      </div>

      <div class="panel mb-3">
        <h2 class="titulo-panel">Foto principal</h2>
        <?php if ($datos['foto_principal']): ?>
          <img class="vista-previa-foto mb-2" id="previaFoto"
               src="<?= ADMIN_URL ?>/uploads/fotos/<?= e(rawurlencode($datos['foto_principal'])) ?>" alt="">
          <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" id="quitar_foto" name="quitar_foto" value="1">
            <label class="form-check-label" for="quitar_foto">Quitar la foto actual</label>
          </div>
        <?php else: ?>
          <img class="vista-previa-foto mb-2 d-none" id="previaFoto" src="" alt="">
        <?php endif; ?>
        <input class="form-control" type="file" name="foto_principal"
               accept="image/jpeg,image/png,image/gif,image/webp" data-previsualizar="#previaFoto">
        <p class="ayuda-campo mb-0 mt-2">Se reduce a 1200 px de ancho y se comprime al 80 % automáticamente.</p>
      </div>

      <div class="panel mb-3">
        <h2 class="titulo-panel">PDF adjunto</h2>
        <?php if ($datos['pdf_adjunto']): ?>
          <p class="mb-2">
            <a href="<?= ADMIN_URL ?>/uploads/pdf/<?= e(rawurlencode($datos['pdf_adjunto'])) ?>" target="_blank">
              <i class="fa-regular fa-file-pdf text-danger me-1"></i>Ver el PDF actual
            </a>
          </p>
          <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" id="quitar_pdf" name="quitar_pdf" value="1">
            <label class="form-check-label" for="quitar_pdf">Quitar el PDF actual</label>
          </div>
        <?php endif; ?>
        <input class="form-control" type="file" name="pdf_adjunto" accept="application/pdf">
        <p class="ayuda-campo mb-0 mt-2">Opcional. Los lectores lo verán como botón de descarga.</p>
      </div>

      <div class="d-grid gap-2">
        <button class="btn btn-primario" type="submit">
          <?= $esEdicion ? 'Guardar cambios' : 'Publicar reportaje' ?>
        </button>
        <a class="btn btn-secundario" href="index.php">Cancelar</a>
      </div>
    </div>
  </div>
</form>

<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/translations/es.js"></script>
<script>
(function () {
  var URL_SUBIDA = '<?= ADMIN_URL ?>/modules/galeria/subir_ajax.php';
  var CSRF = '<?= e(csrf_token()) ?>';

  /* Adaptador de subida: manda la imagen a la galería y devuelve su URL.
     Así el redactor puede arrastrar una foto directamente al texto. */
  function AdaptadorGaleria(loader) {
    this.loader = loader;
  }

  AdaptadorGaleria.prototype.upload = function () {
    var loader = this.loader;
    return loader.file.then(function (archivo) {
      var datos = new FormData();
      datos.append('upload', archivo);
      datos.append('csrf', CSRF);

      return fetch(URL_SUBIDA, { method: 'POST', body: datos, credentials: 'same-origin' })
        .then(function (r) { return r.json(); })
        .then(function (json) {
          if (json.error) { throw new Error(json.error.message); }
          return { default: json.url };
        });
    });
  };

  AdaptadorGaleria.prototype.abort = function () {};

  function conectarAdaptador(editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = function (loader) {
      return new AdaptadorGaleria(loader);
    };
  }

  ClassicEditor
    .create(document.querySelector('#desarrollo'), {
      language: 'es',
      extraPlugins: [conectarAdaptador],
      toolbar: [
        'heading', '|',
        'bold', 'italic', 'link', 'blockQuote', '|',
        'bulletedList', 'numberedList', 'outdent', 'indent', '|',
        'uploadImage', 'insertTable', 'mediaEmbed', '|',
        'undo', 'redo'
      ],
      image: {
        toolbar: ['imageTextAlternative', 'toggleImageCaption',
                  'imageStyle:inline', 'imageStyle:block', 'imageStyle:side']
      },
      table: { contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells'] }
    })
    .then(function (editor) {
      window.editorReportaje = editor;
    })
    .catch(function (err) {
      console.error('No se pudo iniciar el editor:', err);
      document.querySelector('#desarrollo').style.display = 'block';
    });
})();
</script>
