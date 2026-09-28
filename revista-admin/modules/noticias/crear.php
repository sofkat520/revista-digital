<?php require_once __DIR__ . '/../../includes/auth.php'; exigir_admin(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Nueva Noticia - D&D Admin</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/plugins/fontawesome/css/all.min.css">
    <link id="theme-style" rel="stylesheet" href="../../assets/css/portal.css">
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
			            <h1 class="app-page-title mb-0">Crear Nueva Noticia</h1>
				    </div>
				    <div class="col-auto">
					     <a class="btn app-btn-secondary" href="index.php">
					        <i class="fas fa-arrow-left me-2"></i>Volver
					     </a>
				    </div>
			    </div>
			    
				<div class="app-card shadow-sm mb-5">
				    <div class="app-card-body p-4">
					    <form action="guardar.php" method="POST" enctype="multipart/form-data">
					    	
					    	<div class="mb-3">
							    <label for="titulo" class="form-label"><strong>Título de la Noticia *</strong></label>
							    <input type="text" class="form-control" id="titulo" name="titulo" required placeholder="Ej: Importante foro internacional sobre diálogo social">
							</div>

							<div class="row">
								<div class="col-md-6 mb-3">
								    <label for="fecha_publicacion" class="form-label"><strong>Fecha de Publicación *</strong></label>
								    <input type="date" class="form-control" id="fecha_publicacion" name="fecha_publicacion" value="<?= date('Y-m-d') ?>" required>
								</div>

								<div class="col-md-6 mb-3">
								    <label for="foto" class="form-label"><strong>Foto Adjunta</strong></label>
								    <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
								</div>
							</div>

							<div class="mb-4">
							    <label for="link_externo" class="form-label"><strong>Enlace Externo (Opcional)</strong></label>
							    <input type="url" class="form-control" id="link_externo" name="link_externo" placeholder="https://ejemplo.com/noticia-original">
							    <div class="form-text">Si la noticia proviene de una fuente externa o red social.</div>
							</div>

							<button type="submit" class="btn app-btn-primary">
								<i class="fas fa-save me-2"></i>Guardar Noticia
							</button>
					    </form>
				    </div>	
				</div>

		    </div>
	    </div>
    </div>

    <script src="../../assets/plugins/popper.min.js"></script>
    <script src="../../assets/plugins/bootstrap/js/bootstrap.min.js"></script>  
    <script src="../../assets/js/app.js"></script> 
</body>
</html>