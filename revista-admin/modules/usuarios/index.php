<?php
require_once __DIR__ . '/../../includes/auth.php'; exigir_admin();
require_once '../../includes/auth.php';
require_once '../../config/database.php';

$sql = "SELECT id, nombres, ap_paterno, ap_materno, email, rol, created_at FROM usuarios ORDER BY created_at DESC";
$stmt = $pdo->query($sql);
$usuarios = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Usuarios - D&D Admin</title>
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
			            <h1 class="app-page-title mb-0">Gestión de Usuarios</h1>
				    </div>
				    <div class="col-auto">
					     <a class="btn app-btn-primary" href="crear.php">
					        <i class="fas fa-user-plus me-2"></i>Nuevo Usuario
					     </a>
				    </div>
			    </div>

			    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'creado'): ?>
			        <div class="alert alert-success alert-dismissible fade show" role="alert">
			            <strong>¡Éxito!</strong> El usuario se ha registrado correctamente.
			            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			        </div>
			    <?php endif; ?>
			    
				<div class="app-card shadow-sm mb-5">
				    <div class="app-card-body">
					    <div class="table-responsive">
					        <table class="table app-table-hover mb-0 text-left">
								<thead>
									<tr>
										<th class="cell">ID</th>
										<th class="cell">Nombre Completo</th>
										<th class="cell">Correo Electrónico</th>
										<th class="cell">Rol</th>
										<th class="cell">Fecha Registro</th>
									</tr>
								</thead>
								<tbody>
									<?php if (count($usuarios) > 0): ?>
										<?php foreach ($usuarios as $user): ?>
										<tr>
											<td class="cell">#<?= $user['id'] ?></td>
											<td class="cell">
												<strong><?= htmlspecialchars($user['nombres'] . ' ' . $user['ap_paterno'] . ' ' . $user['ap_materno']) ?></strong>
											</td>
											<td class="cell"><?= htmlspecialchars($user['email']) ?></td>
											<td class="cell">
												<?php if ($user['rol'] === 'admin'): ?>
													<span class="badge bg-danger">Administrador</span>
												<?php elseif ($user['rol'] === 'editor'): ?>
													<span class="badge bg-warning text-dark">Editor</span>
												<?php else: ?>
													<span class="badge bg-info">Redactor</span>
												<?php endif; ?>
											</td>
											<td class="cell"><?= date('d/m/Y H:i', strtotime($user['created_at'])) ?></td>
										</tr>
										<?php endforeach; ?>
									<?php else: ?>
										<tr>
											<td colspan="5" class="text-center py-4">No hay usuarios registrados aún.</td>
										</tr>
									<?php endif; ?>
								</tbody>
							</table>
				        </div>
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