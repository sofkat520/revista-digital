<?php
require_once __DIR__ . '/../../includes/auth.php'; exigir_sesion();
require_once '../../includes/auth.php';
require_once '../../config/database.php';

// Consulta de reportajes con nombre de autor
try {
    $sql = "
        SELECT r.*, CONCAT(a.nombres, ' ', IFNULL(a.ap_paterno, '')) AS autor_nombre 
        FROM reportajes r 
        LEFT JOIN autores a ON r.autor_id = a.id 
        " . (es_admin() ? '' : 'WHERE r.usuario_id = ' . (int) usuario_id_actual()) . "
        ORDER BY r.fecha_publicacion DESC
    ";
    $stmt = $pdo->query($sql);
    $reportajes = $stmt->fetchAll();
} catch (Exception $e) {
    $reportajes = [];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Reportajes - D&D Admin</title>
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
                        <h1 class="app-page-title mb-0">Gestión de Reportajes</h1>
                    </div>
                    <div class="col-auto">
                         <a class="btn app-btn-primary" href="crear.php">
                            <i class="fas fa-plus me-2"></i>Nuevo Reportaje
                         </a>
                    </div>
                </div>

                <?php if (isset($_GET['msg']) && $_GET['msg'] === 'creado'): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>¡Éxito!</strong> El reportaje se ha registrado correctamente.
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
                                        <th class="cell">Foto</th>
                                        <th class="cell">Título</th>
                                        <th class="cell">Autor</th>
                                        <th class="cell">Fecha</th>
                                        <th class="cell">Estado</th>
                                        <th class="cell text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($reportajes) > 0): ?>
                                        <?php foreach ($reportajes as $item): ?>
                                        <tr>
                                            <td class="cell">#<?= $item['id'] ?></td>
                                            <td class="cell">
                                                <?php if (!empty($item['foto_principal'])): ?>
                                                    <img src="../../uploads/fotos/<?= $item['foto_principal'] ?>" style="width: 50px; height: 35px; object-fit: cover;" class="rounded border">
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Sin foto</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="cell"><strong><?= htmlspecialchars($item['titulo']) ?></strong></td>
                                            <td class="cell"><?= htmlspecialchars($item['autor_nombre'] ?? 'Sin autor') ?></td>
                                            <td class="cell"><small class="text-muted"><?= date('d/m/Y H:i', strtotime($item['fecha_publicacion'])) ?></small></td>
                                            <td class="cell">
                                                <?php if ($item['es_destacado']): ?>
                                                    <span class="badge bg-danger">Destacado</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Normal</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="cell text-center">
                                                <a href="editar.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-warning text-white me-1">
                                                    <i class="fas fa-edit"></i> Editar
                                                </a>
                                                <?php if (es_admin()): ?>
                                                <a href="eliminar.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-danger text-white" onclick="return confirm('¿Seguro que deseas eliminar este reportaje?');">
                                                    <i class="fas fa-trash"></i> Eliminar
                                                </a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center py-4">No hay reportajes registrados aún.</td>
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