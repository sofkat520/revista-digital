<?php
require_once __DIR__ . '/../../includes/auth.php'; exigir_admin();
require_once '../../includes/auth.php';
require_once '../../config/database.php';

$sql = "SELECT b.*, u.nombres AS usuario_registro 
        FROM boletines b 
        INNER JOIN usuarios u ON b.usuario_id = u.id 
        ORDER BY b.fecha_publicacion DESC";

$stmt = $pdo->query($sql);
$boletines = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Boletines - D&D Admin</title>
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
			            <h1 class="app-page-title mb-0">Boletines</h1>
				    </div>
				    <div class="col-auto">
					     <a class="btn app-btn-primary" href="crear.php">
					        <i class="fas fa-plus me-2"></i>Nuevo Boletín
					     </a>
				    </div>
			    </div>

			    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'creado'): ?>
			        <div class="alert alert-success alert-dismissible fade show" role="alert">
			            <strong>¡Éxito!</strong> El boletín se ha guardado correctamente.
			            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			        </div>
			    <?php endif; ?>
			    
				<div class="app-card shadow-sm mb-5">
				    <div class="app-card-body">
					    <div class="table-responsive">
					        <table class="table app-table-hover mb-0 text-left">
								<thead>
									<tr>
										<th class="cell">N° Boletín</th>
										<th class="cell">Resumen</th>
										<th class="cell">Fecha Pub.</th>
										<th class="cell">PDF</th>
									</tr>
								</thead>
								<tbody>
									<?php if (count($boletines) > 0): ?>
										<?php foreach ($boletines as $item): ?>
										<tr>
											<td class="cell"><strong><?= htmlspecialchars($item['numero_boletin']) ?></strong></td>
											<td class="cell">
												<span class="truncate" style="max-width: 300px; display: inline-block;">
													<?= htmlspecialchars($item['resumen'] ?? 'Sin resumen') ?>
												</span>
											</td>
											<td class="cell"><?= date('d/m/Y', strtotime($item['fecha_publicacion'])) ?></td>
											<td class="cell">
												<a href="../../uploads/pdfs/<?= $item['archivo_pdf'] ?>" target="_blank" class="btn-sm app-btn-secondary">
													<i class="fas fa-file-pdf text-danger me-1"></i>Ver PDF
												</a>
											</td>
										</tr>
										<?php endforeach; ?>
									<?php else: ?>
										<tr>
											<td colspan="4" class="text-center py-4">No hay boletines registrados aún.</td>
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