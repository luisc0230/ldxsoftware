# 🔍 Verificar Configuración - Links con /web/

## ⚠️ Problema

Los enlaces están generando URLs con `/web/`:
- ❌ `https://ldxsoftware.com.pe/web/terminos`
- ✅ Debería ser: `https://ldxsoftware.com.pe/terminos`

## 🎯 Causa

El archivo `config/config.php` en el servidor todavía tiene:
```php
define('BASE_URL', 'https://ldxsoftware.com.pe/web/');
```

## ✅ Solución

### 1. Actualizar `config/config.php` en el servidor

**Ubicación en Ferozo:** `public_html/config/config.php`

**Busca la línea 18:**
```php
define('BASE_URL', 'https://ldxsoftware.com.pe/web/');
```

**Cámbiala a:**
```php
define('BASE_URL', 'https://ldxsoftware.com.pe/');
```

### 2. Verificar función asset()

En el mismo archivo, busca la línea 106 y verifica que diga:
```php
function asset($path) {
    return BASE_URL . 'assets/' . ltrim($path, '/');
}
```

**NO debe decir** `public/assets/`, solo `assets/`

### 3. Guardar y probar

Después de guardar el archivo, prueba:
- ✅ `https://ldxsoftware.com.pe/terminos`
- ✅ `https://ldxsoftware.com.pe/privacidad`

## 📋 Checklist

- [ ] Editar `public_html/config/config.php`
- [ ] Cambiar `BASE_URL` a `'https://ldxsoftware.com.pe/'` (sin /web/)
- [ ] Verificar función `asset()` use `'assets/'` (sin public/)
- [ ] Guardar archivo
- [ ] Limpiar caché del navegador (Ctrl + F5)
- [ ] Probar los enlaces del footer

## 🐛 Si sigue sin funcionar

### Limpiar caché del navegador
1. Presiona `Ctrl + Shift + Delete`
2. Selecciona "Caché" o "Archivos en caché"
3. Limpia
4. Recarga la página con `Ctrl + F5`

### Verificar que subiste el archivo correcto
1. Descarga `config/config.php` desde el servidor
2. Abre el archivo y verifica la línea 18
3. Debe decir: `define('BASE_URL', 'https://ldxsoftware.com.pe/');`

### Verificar permisos
El archivo debe tener permisos `644`:
```bash
chmod 644 config/config.php
```

## 📄 Archivo actualizado

El archivo `config/config.php` en tu proyecto local ya está actualizado con:
- ✅ `BASE_URL = 'https://ldxsoftware.com.pe/'`
- ✅ `asset()` usa `'assets/'`

Solo necesitas **subir este archivo al servidor** o editarlo directamente en Ferozo.

## 🎉 Resultado esperado

Después de actualizar:
- Footer: `Términos y Condiciones` → `https://ldxsoftware.com.pe/terminos`
- Footer: `Política de Privacidad` → `https://ldxsoftware.com.pe/privacidad`
- Assets: `https://ldxsoftware.com.pe/assets/images/logo.png`

Todo sin `/web/` en la URL.
