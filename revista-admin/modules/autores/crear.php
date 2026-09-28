<?php require_once __DIR__ . '/../../includes/auth.php'; exigir_admin(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Nuevo Autor - D&D Admin</title>
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
			            <h1 class="app-page-title mb-0">Crear Nuevo Autor</h1>
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
					    	
					    	<div class="mb-3">
							    <label for="nombres" class="form-label"><strong>Nombres *</strong></label>
							    <input type="text" class="form-control" id="nombres" name="nombres" required placeholder="Ej: Maria">
							</div>

							<div class="row">
								<div class="col-md-6 mb-3">
								    <label for="ap_paterno" class="form-label"><strong>Apellido Paterno</strong></label>
								    <input type="text" class="form-control" id="ap_paterno" name="ap_paterno" placeholder="Ej: Garcia">
								</div>

								<div class="col-md-6 mb-3">
								    <label for="ap_materno" class="form-label"><strong>Apellido Materno</strong></label>
								    <input type="text" class="form-control" id="ap_materno" name="ap_materno" placeholder="Ej: Lopez">
								</div>
							</div>

							<div class="mb-3">
							    <label for="nickname" class="form-label"><strong>Apodo / Nickname</strong></label>
							    <input type="text" class="form-control" id="nickname" name="nickname" placeholder="Ej: M. Garcia o Seudónimo">
							</div>

							<div class="form-check mb-4">
							    <input class="form-check-input" type="checkbox" id="es_nickname" name="es_nickname" value="1">
							    <label class="form-check-label" for="es_nickname">
								    <strong>Usar la firma con Nickname públicamente</strong>
							    </label>
							</div>

							<button type="submit" class="btn app-btn-primary">
								<i class="fas fa-save me-2"></i>Guardar Autor
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