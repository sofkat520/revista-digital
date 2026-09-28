<?php
require_once 'includes/auth.php';
exigir_sesion();
?>

<?php require_once 'config/database.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Panel de Administración - Diálogo y Desarrollo</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">
    <link id="theme-style" rel="stylesheet" href="assets/css/portal.css">
</head> 

<body class="app">   
    <header class="app-header fixed-top">	   
        <?php include_once 'includes/sidebar.php'; ?>
    </header>
    
    <div class="app-wrapper">
	    <div class="app-content pt-3 p-md-3 p-lg-4">
		    <div class="container-xl">
			    <h1 class="app-page-title">Panel de Control</h1>
			    
			    <div class="app-card shadow-sm mb-4 border-left-decoration">
				    <div class="inner">
					    <div class="app-card-body p-4">
						    <h3 class="mb-3">¡Bienvenido al administrador de Diálogo y Desarrollo!</h3>
						    <p>Desde el menú lateral podrás gestionar todos los contenidos de la revista digital: reportajes, boletines en PDF, noticias, multimedia y autores.</p>
					    </div>
				    </div>
			    </div>
		    </div>
	    </div>
    </div>

    <script src="assets/plugins/popper.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>  
    <script src="assets/js/app.js"></script> 
</body>
</html>
