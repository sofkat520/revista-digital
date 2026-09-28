-- ============================================================
-- Revista digital DDP Noticias - Esquema de base de datos
-- Motor: MySQL 8.0+ / MariaDB 10.4+
--
-- IMPORTANTE (hosting compartido): este script NO crea la base de datos.
-- Crea la base y el usuario desde cPanel > "Bases de datos MySQL",
-- selecciona esa base en phpMyAdmin y luego usa la pestana Importar.
-- ============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE IF NOT EXISTS usuarios (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombres         VARCHAR(100) NOT NULL,
    ap_paterno      VARCHAR(100) NOT NULL,
    ap_materno      VARCHAR(100) NULL,
    email           VARCHAR(150) NOT NULL,
    password_hash   VARCHAR(255) NOT NULL,
    rol             ENUM('admin', 'editor', 'redactor') NOT NULL DEFAULT 'redactor',
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT uq_usuarios_email UNIQUE (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS autores (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombres         VARCHAR(100) NOT NULL,
    ap_paterno      VARCHAR(100) NULL,
    ap_materno      VARCHAR(100) NULL,
    nickname        VARCHAR(100) NULL,
    es_nickname     TINYINT(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS reportajes (
    id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    titulo              VARCHAR(255) NOT NULL,
    resumen_corto       VARCHAR(500) NULL,
    desarrollo          LONGTEXT NOT NULL,
    foto_principal      VARCHAR(255) NULL,
    pdf_adjunto         VARCHAR(255) NULL,
    fecha_publicacion   DATE NOT NULL,
    es_destacado        TINYINT(1) NOT NULL DEFAULT 0,
    autor_id            INT UNSIGNED NOT NULL,
    usuario_id          INT UNSIGNED NOT NULL,
    created_at          DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_reportajes_autor
        FOREIGN KEY (autor_id) REFERENCES autores(id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_reportajes_usuario
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    INDEX idx_reportajes_fecha (fecha_publicacion),
    INDEX idx_reportajes_destacado (es_destacado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS reportajes_fotos (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    reportaje_id    INT UNSIGNED NOT NULL,
    url_foto        VARCHAR(255) NOT NULL,
    orden           SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    descripcion     VARCHAR(255) NULL,
    CONSTRAINT fk_reportajes_fotos_reportaje
        FOREIGN KEY (reportaje_id) REFERENCES reportajes(id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    INDEX idx_reportajes_fotos_reportaje (reportaje_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS noticias (
    id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    titulo              VARCHAR(255) NOT NULL,
    foto                VARCHAR(255) NULL,
    link_externo        VARCHAR(500) NULL,
    fecha_publicacion   DATE NOT NULL,
    usuario_id          INT UNSIGNED NOT NULL,
    CONSTRAINT fk_noticias_usuario
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    INDEX idx_noticias_fecha (fecha_publicacion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS boletines (
    id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    numero_boletin      VARCHAR(50) NOT NULL,
    resumen             VARCHAR(500) NULL,
    foto_portada        VARCHAR(255) NULL,
    archivo_pdf         VARCHAR(255) NOT NULL,
    fecha_publicacion   DATE NOT NULL,
    usuario_id          INT UNSIGNED NOT NULL,
    CONSTRAINT uq_boletines_numero UNIQUE (numero_boletin),
    CONSTRAINT fk_boletines_usuario
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    INDEX idx_boletines_fecha (fecha_publicacion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS podcasts (
    id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    titulo              VARCHAR(255) NOT NULL,
    url_embed           VARCHAR(500) NOT NULL,
    fecha_publicacion   DATE NOT NULL,
    usuario_id          INT UNSIGNED NOT NULL,
    CONSTRAINT fk_podcasts_usuario
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    INDEX idx_podcasts_fecha (fecha_publicacion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS videos (
    id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    titulo              VARCHAR(255) NOT NULL,
    url_embed           VARCHAR(500) NOT NULL,
    fecha_publicacion   DATE NOT NULL,
    usuario_id          INT UNSIGNED NOT NULL,
    CONSTRAINT fk_videos_usuario
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    INDEX idx_videos_fecha (fecha_publicacion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Biblioteca de imagenes del panel (modulo Galeria). No estaba en el modelo original.
CREATE TABLE IF NOT EXISTS galeria (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    titulo        VARCHAR(255) NULL,
    archivo       VARCHAR(255) NOT NULL,
    fecha_subida  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
