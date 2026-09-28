<?php
require_once __DIR__ . '/includes/auth.php';
// Si ya inició sesión, enviarlo al panel
if (usuario_id_actual() > 0) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Login - D&D Admin Panel</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">
    <link id="theme-style" rel="stylesheet" href="assets/css/portal.css">
</head> 

<body class="app app-login p-0">    	
    <div class="row g-0 app-auth-wrapper min-vh-100 align-items-center justify-content-center">
	    <div class="col-12 col-md-7 col-lg-5 col-xl-4 auth-main-col p-4 shadow-lg bg-white rounded">
		    <div class="app-auth-body mx-auto">	
			    <div class="app-auth-branding mb-4 text-center">
				    <a class="app-logo" href="#"><span class="logo-text">D&D ADMIN</span></a>
			    </div>
			    <h2 class="auth-heading text-center mb-4">Iniciar Sesión</h2>
			    
			    <?php if (isset($_GET['error'])): ?>
			        <div class="alert alert-danger alert-dismissible fade show" role="alert">
			            <strong>Error:</strong> Credenciales incorrectas. Inténtalo de nuevo.
			            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			        </div>
			    <?php endif; ?>

			    <div class="auth-form-container text-start">
				    <form action="login_process.php" method="POST" class="auth-form login-form">
					    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">					
					    <div class="email mb-3">
                            <label class="sr-only" for="email">Correo</label>
                            <input id="email" name="email" type="email" class="form-control" placeholder="Correo electrónico" required="required">
                        </div>
					    
					    <div class="password mb-3">
						    <label class="sr-only" for="password">Contraseña</label>
						    <input id="password" name="password" type="password" class="form-control signin-password" placeholder="Contraseña" required="required">
					    </div>
					    
					    <div class="text-center mb-4">
						    <button type="submit" class="btn app-btn-primary w-100 theme-btn mx-auto">Ingresar</button>
					    </div>
				    </form>
			    </div>
		    </div>
	    </div>
    </div>
    
    <script src="assets/plugins/popper.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>


