<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo_pagina ?? 'Diálogo y Desarrollo | DDP Noticias' ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <nav class="navbar navbar-expand-lg main-navbar py-2">
        <div class="container">
            <!-- Logo oficial DDP NOTICIAS -->
            <a class="navbar-brand py-0" href="index.php">
                <img src="assets/img/logo.png" alt="DDP Noticias" height="60" onerror="this.onerror=null; this.src='https://via.placeholder.com/60/d30615/fff?text=DDP';">
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link active" href="index.php">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Actualidad</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php">Reportajes</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Podcast</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Boletín NTEP</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Alianzas</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Sobre D&D</a></li>
                    <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
                        <a href="#" class="btn-contact">Contacto</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Franja Gris para el Título Principal -->
    <div class="header-title-bar">
        <div class="container">
            <h1 class="page-title m-0">Reportajes</h1>
        </div>
    </div>