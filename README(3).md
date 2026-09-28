# 📰 DDP Noticias · Revista digital Diálogo y Desarrollo

Portal de reportajes con panel de administración por roles. Proyecto académico del curso **Plataformas para el Desarrollo de Aplicaciones** (Ingeniería de Sistemas, 2026-II), desarrollado en **PHP 8 + MySQL** y desplegado en **InfinityFree**.

## 🔗 Enlaces rápidos

| | Enlace |
|---|---|
| 🌐 Portal público | https://ddpnoticias.free.nf/ |
| 🔐 Panel de administración (login) | https://ddpnoticias.free.nf/revista-admin/login.php |
| 📚 Todos los reportajes | https://ddpnoticias.free.nf/revista/reportajes.php |
| 💻 Repositorio | https://github.com/TU-USUARIO/revista-digital |

## 🔑 Cuentas de demostración

| Rol | Correo | Contraseña |
|---|---|---|
| Administrador | `admin@dialogoydesarrollo.com.pe` | `Admin#2026Demo` |
| Redactor | `redactor@dialogoydesarrollo.com.pe` | `Redactor#2026Demo` |

> Cuentas creadas solo para la evaluación del curso. Se cambiarán o eliminarán después (Panel → Usuarios).

## 👥 Roles y permisos

| | Administrador | Redactor |
|---|:-:|:-:|
| Crear reportajes | ✅ | ✅ |
| Editar reportajes | Todos | Solo los suyos |
| Ver la lista de reportajes | Todos | Solo los suyos |
| Eliminar reportajes / marcar destacado | ✅ | ❌ |
| Autores, noticias, boletines, multimedia, galería, usuarios | ✅ | ❌ |

## ✨ Características

- **Portal público**: reportaje destacado, últimos reportajes, listado completo en cuadrícula responsive (3 / 2 / 1 columnas) con paginación, y detalle con PDF adjunto.
- **Panel de administración** con login por correo y dos roles (admin / redactor).
- Seguridad: contraseñas con hash, sesiones con regeneración de ID, protección CSRF, consultas preparadas (PDO) y carpeta `uploads/` que no ejecuta PHP.
- Subida de fotos y PDF por reportaje.

## 🧰 Tecnologías

PHP 8 · MySQL / MariaDB · PDO · Bootstrap 5 · Font Awesome · Git y GitHub · InfinityFree (hosting) · FileZilla (FTP)

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
│   ├── database.sql       # Esquema (9 tablas)
│   └── datos_ejemplo.sql  # Usuarios demo + 8 reportajes de ejemplo
└── docs/capturas/         # Evidencias del despliegue
```

## 🗄️ Modelo de datos

`usuarios` · `autores` · `reportajes` · `reportajes_fotos` · `noticias` · `boletines` · `podcasts` · `videos` · `galeria`

Un usuario publica muchos contenidos (1:N), un autor escribe muchos reportajes (1:N) y un reportaje tiene muchas fotos (1:N).

## 🚀 Despliegue en InfinityFree (lo que se hizo)

1. **Cuenta de hosting**: se creó en infinityfree.com con el subdominio `ddpnoticias.free.nf`.
2. **Base de datos**: Control Panel → *MySQL Databases* → crear la base (queda con prefijo `if0_XXXXXXXX_`). En **phpMyAdmin** se importó `sql/database.sql` y luego `sql/datos_ejemplo.sql` (9 tablas).
3. **Configuración**: en cada carpeta `config/`, copiar `database.example.php` como `database.php` y completar host (`sqlXXX.infinityfree.com`, no `localhost`), base, usuario y contraseña. Este archivo está en `.gitignore`: **las credenciales reales nunca se suben a GitHub**.
4. **Subida de archivos** por FTP con FileZilla a la carpeta `htdocs`: `index.php`, `revista/` y `revista-admin/`.
5. **Pruebas en producción**: portal, "Ver todos", login como admin (crear, editar y eliminar un reportaje) y como redactor (solo ve Reportajes).

## 💻 Ejecutar en local (XAMPP)

1. Clonar el repositorio dentro de `xampp/htdocs`.
2. Crear la base `revista_digital` en phpMyAdmin e importar `sql/database.sql` y `sql/datos_ejemplo.sql`.
3. Copiar `config/database.example.php` como `database.php` en `revista/config` y `revista-admin/config` (los valores por defecto sirven en XAMPP: host `localhost`, usuario `root`, sin clave).
4. Abrir `http://localhost/revista/` y `http://localhost/revista-admin/login.php`.

## 🛠️ Solución de problemas

| Síntoma | Causa probable | Solución |
|---|---|---|
| "No se pudo conectar con la base de datos" | Datos de `database.php` incorrectos | Revisar host, base (con prefijo `if0_…`), usuario y contraseña |
| Error 500 | `.htaccess` o sintaxis PHP | Revisar el log de errores del hosting |
| Fotos o PDF no cargan | `revista` y `revista-admin` no son carpetas hermanas | Mantener la estructura del repositorio |
| Tildes rotas | Codificación | Base en `utf8mb4` y archivos en UTF-8 |
| La galería no sube imágenes | Extensión `gd` desactivada | Activarla en la configuración de PHP |

## 📸 Capturas del despliegue

Ver la carpeta [`docs/capturas`](docs/capturas): panel de InfinityFree, phpMyAdmin con las tablas, portal en vivo y panel de administración.

## 👤 Autor

TU NOMBRE · Ingeniería de Sistemas · Curso: Plataformas para el Desarrollo de Aplicaciones

## 📄 Licencia

Uso académico / educativo.
