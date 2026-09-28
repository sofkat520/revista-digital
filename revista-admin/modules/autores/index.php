<?php
require_once __DIR__ . '/../../includes/auth.php'; exigir_admin();
require_once '../../includes/auth.php';
require_once '../../config/database.php';
$sql = "SELECT * FROM autores ORDER BY nombres ASC";
$stmt = $pdo->query($sql);
$autores = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Autores - D&D Admin</title>
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
			            <h1 class="app-page-title mb-0">Gestión de Autores</h1>
				    </div>
				    <div class="col-auto">
					     <a class="btn app-btn-primary" href="crear.php">
					        <i class="fas fa-plus me-2"></i>Nuevo Autor
					     </a>
				    </div>
			    </div>

			    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'creado'): ?>
			        <div class="alert alert-success alert-dismissible fade show" role="alert">
			            <strong>¡Éxito!</strong> El autor se ha registrado correctamente.
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
										<th class="cell">Apodo / Nickname</th>
										<th class="cell">¿Usa Nickname?</th>
									</tr>
								</thead>
								<tbody>
									<?php if (count($autores) > 0): ?>
										<?php foreach ($autores as $autor): ?>
										<tr>
											<td class="cell">#<?= $autor['id'] ?></td>
											<td class="cell">
												<?= htmlspecialchars($autor['nombres'] . ' ' . $autor['ap_paterno'] . ' ' . $autor['ap_materno']) ?>
											</td>
											<td class="cell"><?= htmlspecialchars($autor['nickname'] ?? '-') ?></td>
											<td class="cell">
												<?php if ($autor['es_nickname']): ?>
													<span class="badge bg-info">Sí</span>
												<?php else: ?>
													<span class="badge bg-secondary">No</span>
												<?php endif; ?>
											</td>
										</tr>
										<?php endforeach; ?>
									<?php else: ?>
										<tr>
											<td colspan="4" class="text-center py-4">No hay autores registrados aún.</td>
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