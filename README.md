# LDX Software - Landing Page

Una landing page moderna y profesional desarrollada en PHP con arquitectura MVC, diseñada para mostrar los servicios y proyectos de LDX Software.

## 🚀 Características

- **Arquitectura MVC**: Código organizado y mantenible
- **Diseño Responsive**: Optimizado para todos los dispositivos
- **Animaciones Modernas**: AOS y GSAP para efectos visuales
- **SEO Optimizado**: Meta tags y estructura semántica
- **Formularios Validados**: Validación en cliente y servidor
- **Configuración Flexible**: Fácil cambio de BASE_URL para deployment
- **Seguridad**: Protección CSRF y sanitización de datos

## 📁 Estructura del Proyecto

```
ldx/
├── app/
│   ├── controllers/        # Controladores MVC
│   │   ├── BaseController.php
│   │   ├── HomeController.php
│   │   ├── PortfolioController.php
│   │   ├── ContactController.php
│   │   └── ErrorController.php
│   ├── models/            # Modelos de datos
│   │   ├── ServiceModel.php
│   │   ├── PortfolioModel.php
│   │   ├── TestimonialModel.php
│   │   └── ContactModel.php
│   ├── views/             # Vistas y templates
│   │   ├── layouts/
│   │   ├── portfolio/
│   │   ├── errors/
│   │   ├── home.php
│   │   └── contact.php
│   ├── includes/          # Archivos de inclusión
│   │   ├── header.php
│   │   ├── navbar.php
│   │   ├── footer.php
│   │   └── scripts.php
│   └── data/              # Almacenamiento de datos (JSON)
├── config/                # Configuración
│   ├── config.php
│   └── routes.php
├── public/                # Archivos públicos
│   ├── assets/
│   │   ├── css/
│   │   ├── js/
│   │   └── images/
│   ├── index.php
│   └── .htaccess
└── README.md
```

## 🛠️ Instalación

### Requisitos
- PHP 7.4 o superior
- Apache/Nginx con mod_rewrite
- Extensiones PHP: json, mbstring, openssl

### Pasos de Instalación

1. **Clonar o descargar el proyecto**
   ```bash
   git clone https://github.com/luisc0230/ldxsoftware.git ldx
   cd ldx
   ```

2. **Configurar el servidor web**
   - Apuntar el DocumentRoot a la carpeta `public/`
   - O colocar el proyecto en `htdocs/ldx/` si usas XAMPP

3. **Configurar la aplicación**
   - Editar `config/config.php`
   - Cambiar `BASE_URL` según tu entorno:
   ```php
   // Para desarrollo local
   define('BASE_URL', 'http://localhost/ldx/');
   
   // Para producción
   define('BASE_URL', 'https://tudominio.com/');
   ```

4. **Configurar permisos**
   ```bash
   chmod 755 app/data/
   chmod 644 app/data/*.json
   ```

5. **Configurar email (opcional)**
   - Editar las constantes SMTP en `config/config.php`
   - Configurar PHPMailer si deseas envío real de emails

## 📧 Configuración de Email

Para habilitar el envío de emails, configura las siguientes constantes en `config/config.php`:

```php
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'tu-email@gmail.com');
define('SMTP_PASSWORD', 'tu-password-de-aplicacion');
define('SMTP_FROM_EMAIL', 'contacto@ldxsoftware.com.pe');
define('SMTP_FROM_NAME', 'LDX Software');
```

## 🎨 Personalización

### Colores y Estilos
Los colores principales se configuran en `app/includes/header.php` usando TailwindCSS:

```javascript
tailwind.config = {
    theme: {
        extend: {
            colors: {
                primary: { /* Azul principal */ },
                secondary: { /* Gris secundario */ },
                accent: { /* Morado de acento */ }
            }
        }
    }
}
```

### Contenido
- **Servicios**: Editar `app/models/ServiceModel.php`
- **Portfolio**: Editar `app/models/PortfolioModel.php`
- **Testimonios**: Editar `app/models/TestimonialModel.php`
- **Información de contacto**: Editar controladores y vistas

### Imágenes
Colocar las imágenes en `public/assets/images/` con la siguiente estructura:
```
images/
├── logo.png
├── logo-white.png
├── hero-bg.jpg
├── about-team.jpg
├── portfolio/
│   ├── thumbs/
│   └── [project-images]
└── testimonials/
    └── [client-photos]
```

## 🔧 Funcionalidades

### Rutas Disponibles
- `/` - Página de inicio
- `/about` - Acerca de nosotros
- `/services` - Servicios
- `/portfolio` - Portfolio de proyectos
- `/portfolio/project/{id}` - Detalle de proyecto
- `/contact` - Formulario de contacto
- `/api/contact` - API para envío de formularios
- `/api/newsletter` - API para suscripción al newsletter

### Formulario de Contacto
- Validación en tiempo real con JavaScript
- Validación del lado del servidor
- Protección CSRF
- Almacenamiento en archivos JSON
- Envío de notificaciones por email

### SEO y Performance
- Meta tags optimizados
- Open Graph y Twitter Cards
- Structured Data (Schema.org)
- Lazy loading de imágenes
- Compresión y cache de assets
- Service Worker ready

## 🚀 Deployment

### Para Producción

1. **Subir archivos al servidor**
   ```bash
   rsync -avz --exclude 'README.md' ./ usuario@servidor:/path/to/website/
   ```

2. **Configurar BASE_URL**
   ```php
   define('BASE_URL', 'https://tudominio.com/');
   ```

3. **Configurar modo producción**
   ```php
   define('DEBUG_MODE', false);
   ```

4. **Configurar SSL y headers de seguridad**
   - Habilitar HTTPS
   - Configurar headers en `.htaccess`

5. **Optimizar performance**
   - Habilitar compresión gzip
   - Configurar cache de navegador
   - Optimizar imágenes

### Variables de Entorno
Para mayor seguridad, puedes usar variables de entorno:

```php
define('DB_PASS', $_ENV['DB_PASSWORD'] ?? '');
define('SMTP_PASSWORD', $_ENV['SMTP_PASSWORD'] ?? '');
```

## 📱 Responsive Design

El sitio está optimizado para:
- **Desktop**: 1200px+
- **Tablet**: 768px - 1199px
- **Mobile**: 320px - 767px

Utiliza TailwindCSS con clases responsive:
- `sm:` - 640px+
- `md:` - 768px+
- `lg:` - 1024px+
- `xl:` - 1280px+

## 🔒 Seguridad

### Medidas Implementadas
- Protección CSRF en formularios
- Sanitización de datos de entrada
- Validación del lado del servidor
- Headers de seguridad en `.htaccess`
- Prevención de acceso directo a archivos PHP

### Recomendaciones Adicionales
- Mantener PHP actualizado
- Usar HTTPS en producción
- Configurar firewall del servidor
- Realizar backups regulares

## 🐛 Debugging

### Modo Debug
Activar en `config/config.php`:
```php
define('DEBUG_MODE', true);
```

### Logs de Error
Los errores se registran en:
- Logs del servidor web
- Console del navegador (JavaScript)
- Archivo de log personalizado (si se configura)

## 📞 Soporte

Para soporte técnico o consultas:
- **Email**: contacto@ldxsoftware.com.pe
- **Teléfono**: +52 (55) 1234-5678
- **Website**: https://ldxsoftware.com

## 📄 Licencia

Este proyecto está desarrollado por LDX Software. Todos los derechos reservados.

---

**Desarrollado con ❤️ por LDX Software**
