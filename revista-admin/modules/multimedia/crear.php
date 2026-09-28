<?php require_once __DIR__ . '/../../includes/auth.php'; exigir_admin(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Nuevo Multimedia - D&D Admin</title>
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
			            <h1 class="app-page-title mb-0">Agregar Contenido Multimedia</h1>
				    </div>
				    <div class="col-auto">
					     <a class="btn app-btn-secondary" href="index.php">
					        <i class="fas fa-arrow-left me-2"></i>Volver
					     </a>
				    </div>
			    </div>
			    
				<div class="app-card shadow-sm mb-5">
				    <div class="app-card-body p-4">
					    <form action="guardar.php" method="POST">
					    	
					    	<div class="row">
						    	<div class="col-md-6 mb-3">
								    <label for="tipo" class="form-label"><strong>Tipo de Multimedia *</strong></label>
								    <select class="form-select" id="tipo" name="tipo" required>
								    	<option value="podcast">Podcast (Audio / Spotify / Soundcloud)</option>
								    	<option value="video">Video (YouTube / Vimeo)</option>
								    </select>
								</div>

								<div class="col-md-6 mb-3">
								    <label for="fecha_publicacion" class="form-label"><strong>Fecha de Publicación *</strong></label>
								    <input type="date" class="form-control" id="fecha_publicacion" name="fecha_publicacion" value="<?= date('Y-m-d') ?>" required>
								</div>
							</div>

							<div class="mb-3">
							    <label for="titulo" class="form-label"><strong>Título *</strong></label>
							    <input type="text" class="form-control" id="titulo" name="titulo" required placeholder="Ej: Episodio 10: Retos del sector laboral">
							</div>

							<div class="mb-4">
							    <label for="url_embed" class="form-label"><strong>URL o Código Embed *</strong></label>
							    <input type="text" class="form-control" id="url_embed" name="url_embed" required placeholder="https://www.youtube.com/embed/XXXXXX o enlace de Spotify">
							    <div class="form-text">Pega la URL de reproducción o incrustación que provee la plataforma.</div>
							</div>

							<button type="submit" class="btn app-btn-primary">
								<i class="fas fa-save me-2"></i>Guardar Multimedia
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