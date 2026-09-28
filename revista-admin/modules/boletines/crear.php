<?php require_once __DIR__ . '/../../includes/auth.php'; exigir_admin(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Nuevo Boletín - D&D Admin</title>
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
			            <h1 class="app-page-title mb-0">Crear Nuevo Boletín</h1>
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
					    	
					    	<div class="row">
						    	<div class="col-md-6 mb-3">
								    <label for="numero_boletin" class="form-label"><strong>Número de Boletín *</strong></label>
								    <input type="text" class="form-control" id="numero_boletin" name="numero_boletin" required placeholder="Ej: N° 045-2026">
								</div>

								<div class="col-md-6 mb-3">
								    <label for="fecha_publicacion" class="form-label"><strong>Fecha de Publicación *</strong></label>
								    <input type="date" class="form-control" id="fecha_publicacion" name="fecha_publicacion" value="<?= date('Y-m-d') ?>" required>
								</div>
							</div>

							<div class="mb-3">
							    <label for="resumen" class="form-label"><strong>Resumen de Contenido</strong></label>
							    <textarea class="form-control" id="resumen" name="resumen" rows="3" placeholder="Puntos principales tratados en este boletín..."></textarea>
							</div>

							<div class="row">
								<div class="col-md-6 mb-3">
								    <label for="foto_portada" class="form-label"><strong>Foto de Portada (Opcional)</strong></label>
								    <input type="file" class="form-control" id="foto_portada" name="foto_portada" accept="image/*">
								</div>

								<div class="col-md-6 mb-3">
								    <label for="archivo_pdf" class="form-label"><strong>Archivo PDF del Boletín *</strong></label>
								    <input type="file" class="form-control" id="archivo_pdf" name="archivo_pdf" accept=".pdf" required>
								</div>
							</div>

							<button type="submit" class="btn app-btn-primary mt-3">
								<i class="fas fa-save me-2"></i>Guardar Boletín
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