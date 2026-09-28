# 📰 DDP Noticias · Revista digital Diálogo y Desarrollo

Portal de reportajes con panel de administración. Proyecto académico del curso **Plataformas para el Desarrollo de Aplicaciones** (Ingeniería de Sistemas, 2026-II), desarrollado en **PHP 8 + MySQL** y desplegado en un hosting compartido.

🌐 **Sitio en vivo:** https://TU-DOMINIO/revista/
🔐 **Panel de administración:** https://TU-DOMINIO/revista-admin/login.php

## ✨ Características

- **Portal público** (`/revista`): reportaje destacado, últimos reportajes, listado completo en cuadrícula responsive (3 / 2 / 1 columnas) con paginación, y página de detalle con PDF adjunto.
- **Panel de administración** (`/revista-admin`) con **dos roles**:

| | Administrador | Redactor |
|---|:-:|:-:|
| Crear reportajes | ✅ | ✅ |
| Editar reportajes | Todos | Solo los suyos |
| Eliminar reportajes / marcar destacado | ✅ | ❌ |
| Autores, noticias, boletines, multimedia, galería, usuarios | ✅ | ❌ |

- Contraseñas con hash (`password_hash`), sesiones con regeneración de ID, protección CSRF y consultas preparadas (PDO).
- Subida de fotos y PDF, con carpeta `uploads/` que no ejecuta código PHP.

## 🧰 Requisitos

- PHP 8.0+ con extensiones `pdo_mysql` y `gd`
- MySQL 5.7+ / MariaDB 10.4+
- Hosting con cPanel + phpMyAdmin (o XAMPP en local)

## 📁 Estructura

```
├── index.php              # Redirige al portal
├── revista/               # Portal público
│   └── config/database.example.php
├── revista-admin/         # Panel de administración
│   ├── config/database.example.php
│   ├── includes/          # auth.php (roles), header, sidebar
│   ├── modules/           # reportajes, autores, usuarios, etc.
│   └── uploads/           # fotos, pdf, galeria (no se versionan)
├── sql/
│   ├── database.sql       # Esquema (tablas)
│   └── datos_ejemplo.sql  # Usuarios demo + 8 reportajes de ejemplo
└── docs/capturas/         # Evidencias del despliegue
```

## 🚀 Despliegue en cPanel (paso a paso)

### 1. Base de datos
1. cPanel → **Bases de datos MySQL** → crear la base (ej. `cuenta_revista`).
2. Crear un usuario con contraseña fuerte y **asignarlo a la base con todos los privilegios**.
3. cPanel → **phpMyAdmin** → seleccionar la base → **Importar** → `sql/database.sql`.
4. Importar después `sql/datos_ejemplo.sql`. Verifica que aparezcan las 9 tablas.

> En hosting compartido cPanel antepone el nombre de tu cuenta a la base y al usuario (`cuenta_revista`, `cuenta_usuario`). Usa esos nombres exactos.

### 2. Código
**Opción A – ZIP + Gestor de archivos**
1. Descarga el repositorio (*Code → Download ZIP*).
2. Gestor de archivos → `public_html` → **Upload** del ZIP → **Extract**.
3. Verifica que queden `public_html/revista`, `public_html/revista-admin` e `index.php`.

**Opción B – Git™ Version Control**
1. cPanel → *Git™ Version Control* → *Create* → *Clone a Repository*.
2. URL: `https://github.com/TU-USUARIO/revista-digital.git`, ruta: `public_html`.
3. Para actualizar: `git push` en local → *Pull or Deploy → Update from Remote*.

### 3. Configuración de la conexión (credenciales reales, fuera de Git)
En el servidor, en **ambas** carpetas, copia la plantilla y edítala:

```
revista/config/database.example.php        →  revista/config/database.php
revista-admin/config/database.example.php  →  revista-admin/config/database.php
```

```php
$host = 'localhost';
$db   = 'cuenta_revista';
$user = 'cuenta_usuario';
$pass = 'TU_CONTRASEÑA';
```

`database.php` está en `.gitignore`: las credenciales nunca se suben a GitHub.

### 4. Permisos y prueba
- Carpetas `755`, archivos `644`; `revista-admin/uploads/*` con permiso de escritura.
- Abre `https://TU-DOMINIO/revista/` y prueba:
  1. Portal: destacado, tarjetas, **Ver todos**, detalle de un reportaje.
  2. Login como **admin** → crear, editar, eliminar un reportaje.
  3. Login como **redactor** → solo ve Reportajes; crea y edita los suyos.

## 🔑 Cuentas de demostración

| Rol | Correo | Contraseña |
|---|---|---|
| Administrador | admin@dialogoydesarrollo.com.pe | `Admin#2026Demo` |
| Redactor | redactor@dialogoydesarrollo.com.pe | `Redactor#2026Demo` |

> Solo para la evaluación. Cambia o elimina estas cuentas después (Panel → Usuarios).

## 🗄️ Modelo de datos

`usuarios` · `autores` · `reportajes` · `reportajes_fotos` · `noticias` · `boletines` · `podcasts` · `videos` · `galeria`
Relaciones: un usuario publica muchos contenidos (1:N); un autor escribe muchos reportajes (1:N); un reportaje tiene muchas fotos (1:N).

## 🛠️ Solución de problemas

| Síntoma | Causa probable | Solución |
|---|---|---|
| Error 500 | Sintaxis PHP o `.htaccess` inválido | Revisar el *error log* en cPanel |
| "No se pudo conectar con la base de datos" | Credenciales o prefijo de cPanel incorrectos | Revisar `config/database.php` |
| Fotos/PDF no cargan | `revista` y `revista-admin` no son carpetas hermanas | Mantener la estructura de este repositorio |
| Tildes rotas (�) | Codificación | Base en `utf8mb4` y `<meta charset="UTF-8">` |
| La galería no sube imágenes | Extensión `gd` desactivada | Activarla en *Select PHP Version → Extensions* |

## 📸 Capturas del despliegue

Ver la carpeta [`docs/capturas`](docs/capturas): cPanel, phpMyAdmin con las tablas, portal en vivo y panel de administración.

## 👤 Autor

TU NOMBRE · Ingeniería de Sistemas · Curso: Plataformas para el Desarrollo de Aplicaciones

## 📄 Licencia

Uso académico / educativo.
