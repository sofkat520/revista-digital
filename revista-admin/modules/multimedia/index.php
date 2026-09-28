<?php
require_once __DIR__ . '/../../includes/auth.php'; exigir_admin();
require_once '../../includes/auth.php';
require_once '../../config/database.php';

// Cargar Podcasts
$podcasts = $pdo->query("SELECT p.*, u.nombres AS usuario_registro 
                        FROM podcasts p 
                        INNER JOIN usuarios u ON p.usuario_id = u.id 
                        ORDER BY p.fecha_publicacion DESC")->fetchAll();

// Cargar Videos
$videos = $pdo->query("SELECT v.*, u.nombres AS usuario_registro 
                      FROM videos v 
                      INNER JOIN usuarios u ON v.usuario_id = u.id 
                      ORDER BY v.fecha_publicacion DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Multimedia - D&D Admin</title>
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
			            <h1 class="app-page-title mb-0">Contenido Multimedia</h1>
				    </div>
				    <div class="col-auto">
					     <a class="btn app-btn-primary" href="crear.php">
					        <i class="fas fa-plus me-2"></i>Nuevo Multimedia
					     </a>
				    </div>
			    </div>

			    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'creado'): ?>
			        <div class="alert alert-success alert-dismissible fade show" role="alert">
			            <strong>¡Éxito!</strong> El contenido multimedia se ha guardado correctamente.
			            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			        </div>
			    <?php endif; ?>

			    <!-- Pestañas para separar Podcasts y Videos -->
			    <nav id="orders-table-tab" class="orders-table-tab app-nav-tabs nav shadow-sm flex-column flex-sm-row mb-4">
				    <a class="flex-sm-fill text-sm-center nav-link active" id="podcasts-tab" data-bs-toggle="tab" href="#tab-podcasts" role="tab" aria-controls="tab-podcasts" aria-selected="true"><i class="fas fa-podcast me-2"></i>Podcasts (<?= count($podcasts) ?>)</a>
				    <a class="flex-sm-fill text-sm-center nav-link" id="videos-tab" data-bs-toggle="tab" href="#tab-videos" role="tab" aria-controls="tab-videos" aria-selected="false"><i class="fas fa-video me-2"></i>Videos (<?= count($videos) ?>)</a>
				</nav>

				<div class="tab-content" id="orders-table-tab-content">
			        
			        <!-- Pestaña Podcasts -->
			        <div class="tab-pane fade show active" id="tab-podcasts" role="tabpanel" aria-labelledby="podcasts-tab">
					    <div class="app-card shadow-sm mb-5">
						    <div class="app-card-body">
							    <div class="table-responsive">
							        <table class="table app-table-hover mb-0 text-left">
										<thead>
											<tr>
												<th class="cell">ID</th>
												<th class="cell">Título</th>
												<th class="cell">Fecha Pub.</th>
												<th class="cell">Embed / Link</th>
											</tr>
										</thead>
										<tbody>
											<?php if (count($podcasts) > 0): ?>
												<?php foreach ($podcasts as $item): ?>
												<tr>
													<td class="cell">#<?= $item['id'] ?></td>
													<td class="cell"><strong><?= htmlspecialchars($item['titulo']) ?></strong></td>
													<td class="cell"><?= date('d/m/Y', strtotime($item['fecha_publicacion'])) ?></td>
													<td class="cell">
														<a href="<?= htmlspecialchars($item['url_embed']) ?>" target="_blank" class="btn-sm app-btn-secondary">
															<i class="fas fa-play text-success me-1"></i>Abrir Recurso
														</a>
													</td>
												</tr>
												<?php endforeach; ?>
											<?php else: ?>
												<tr>
													<td colspan="4" class="text-center py-4">No hay podcasts registrados.</td>
												</tr>
											<?php endif; ?>
										</tbody>
									</table>
						        </div>
						    </div>	
						</div>
			        </div>

			        <!-- Pestaña Videos -->
			        <div class="tab-pane fade" id="tab-videos" role="tabpanel" aria-labelledby="videos-tab">
					    <div class="app-card shadow-sm mb-5">
						    <div class="app-card-body">
							    <div class="table-responsive">
							        <table class="table app-table-hover mb-0 text-left">
										<thead>
											<tr>
												<th class="cell">ID</th>
												<th class="cell">Título</th>
												<th class="cell">Fecha Pub.</th>
												<th class="cell">Embed / Link</th>
											</tr>
										</thead>
										<tbody>
											<?php if (count($videos) > 0): ?>
												<?php foreach ($videos as $item): ?>
												<tr>
													<td class="cell">#<?= $item['id'] ?></td>
													<td class="cell"><strong><?= htmlspecialchars($item['titulo']) ?></strong></td>
													<td class="cell"><?= date('d/m/Y', strtotime($item['fecha_publicacion'])) ?></td>
													<td class="cell">
														<a href="<?= htmlspecialchars($item['url_embed']) ?>" target="_blank" class="btn-sm app-btn-secondary">
															<i class="fas fa-play text-danger me-1"></i>Abrir Recurso
														</a>
													</td>
												</tr>
												<?php endforeach; ?>
											<?php else: ?>
												<tr>
													<td colspan="4" class="text-center py-4">No hay videos registrados.</td>
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
	    </div>
    </div>

    <script src="../../assets/plugins/popper.min.js"></script>
    <script src="../../assets/plugins/bootstrap/js/bootstrap.min.js"></script>  
    <script src="../../assets/js/app.js"></script> 
</body>
</html>