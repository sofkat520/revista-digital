<?php
require_once __DIR__ . '/../../includes/auth.php'; exigir_admin();
require_once '../../includes/auth.php';
require_once '../../config/database.php';

$sql = "SELECT n.*, u.nombres AS usuario_registro 
        FROM noticias n 
        INNER JOIN usuarios u ON n.usuario_id = u.id 
        ORDER BY n.fecha_publicacion DESC";

$stmt = $pdo->query($sql);
$noticias = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Noticias - D&D Admin</title>
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
			            <h1 class="app-page-title mb-0">Gestión de Noticias</h1>
				    </div>
				    <div class="col-auto">
					     <a class="btn app-btn-primary" href="crear.php">
					        <i class="fas fa-plus me-2"></i>Nueva Noticia
					     </a>
				    </div>
			    </div>

			    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'creado'): ?>
			        <div class="alert alert-success alert-dismissible fade show" role="alert">
			            <strong>¡Éxito!</strong> La noticia se ha guardado correctamente.
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
										<th class="cell">Título</th>
										<th class="cell">Fecha Pub.</th>
										<th class="cell">Enlace Externo</th>
										<th class="cell">Foto</th>
									</tr>
								</thead>
								<tbody>
									<?php if (count($noticias) > 0): ?>
										<?php foreach ($noticias as $item): ?>
										<tr>
											<td class="cell">#<?= $item['id'] ?></td>
											<td class="cell">
												<span class="truncate" style="max-width: 300px; display: inline-block;">
													<?= htmlspecialchars($item['titulo']) ?>
												</span>
											</td>
											<td class="cell"><?= date('d/m/Y', strtotime($item['fecha_publicacion'])) ?></td>
											<td class="cell">
												<?php if ($item['link_externo']): ?>
													<a href="<?= htmlspecialchars($item['link_externo']) ?>" target="_blank" class="btn-sm app-btn-secondary">
														<i class="fas fa-external-link-alt me-1"></i>Visitar Link
													</a>
												<?php else: ?>
													<span class="text-muted">-</span>
												<?php endif; ?>
											</td>
											<td class="cell">
												<?php if ($item['foto']): ?>
													<i class="fas fa-image text-primary" title="Foto adjunta"></i>
												<?php else: ?>
													<span class="text-muted">-</span>
												<?php endif; ?>
											</td>
										</tr>
										<?php endforeach; ?>
									<?php else: ?>
										<tr>
											<td colspan="5" class="text-center py-4">No hay noticias registradas aún.</td>
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