# Guía de Implementación SEO en CodeIgniter 3

Esta guía documenta exclusivamente la implementación técnica de **SEO (Search Engine Optimization)** realizada en el proyecto Emetix. El objetivo es estructurar el sitio para ser indexado correctamente por motores de búsqueda (Google) y redes sociales.

## 1. Resumen de Cambios
Se modificaron 4 áreas estratégicas:
1.  **Vistas (`header.php`)**: Inyección de meta etiquetas dinámicas.
2.  **Controladores (`Product.php`, `Home.php`)**: Lógica para generar los datos SEO.
3.  **Seguridad**: Sanitización contra XSS.
4.  **Archivos Públicos (`robots.txt`)**: Control de rastreo.

---

## 2. Detalle de Implementación

### A. Vista Principal: `application/views/layout/header.php`
Se reemplazó el bloque estático `<title>` por una estructura dinámica y segura.

**Código Implementado:**
```php
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- SEO Dinámico -->
    <title><?= isset($meta_title) ? html_escape($meta_title) : 'Emetix - Ecomerce Moderno' ?></title>
    
    <!-- Meta Descripción (Crucial para CTR en Google) -->
    <meta name="description" content="<?= isset($meta_description) ? html_escape(strip_tags($meta_description)) : 'Encuentra los mejores productos de electrónica al mejor precio en Emetix.' ?>">
    
    <!-- Canonical URL (Evita contenido duplicado) -->
    <link rel="canonical" href="<?= current_url() ?>">

    <!-- Open Graph (Facebook, WhatsApp) -->
    <meta property="og:title" content="<?= isset($meta_title) ? html_escape($meta_title) : 'Emetix' ?>">
    <meta property="og:description" content="<?= isset($meta_description) ? html_escape(strip_tags($meta_description)) : 'Tu tienda de confianza.' ?>">
    <meta property="og:image" content="<?= isset($og_image) ? html_escape($og_image) : base_url('assets/img/logo-emetix.png') ?>">
    <meta property="og:url" content="<?= current_url() ?>">
    <meta property="og:type" content="website">
    
    <!-- ... Resto de estilos CSS ... -->
</head>
```

### B. Controlador de Productos: `application/controllers/Product.php`
En el método `index()`, se generan los datos SEO específicos del producto.

**Puntos Clave:**
*   **Limpieza:** Uso de `strip_tags()` para eliminar HTML de la descripción.
*   **Longitud:** Recorte a 160 caracteres (`substr`) para cumplir estándares de Google.

**Código Clave:**
```php
// --- BLOQUE SEO ---
$data['meta_title'] = $data['product']->name . ' | Emetix';
$data['meta_description'] = substr(strip_tags($data['product']->description), 0, 160) . '...';
$data['meta_keywords'] = 'comprar ' . $data['product']->name . ', precio ' . $data['product']->name . ', electronica';
$data['og_image'] = $data['product']->image_url;
// ------------------
```

### C. Datos Estructurados (Schema.org)
En `application/views/product/detail.php` se agregó **JSON-LD** al final. Esto permite Rich Snippets (Precio, Stock).

**Código Implementado:**
```html
<script type="application/ld+json">
{
  "@context": "https://schema.org/",
  "@type": "Product",
  "name": "<?= html_escape($product->name) ?>",
  "image": [ "<?= $product->image_url ?>" ],
  "description": "<?= html_escape(str_replace('"', '\"', strip_tags($product->description))) ?>",
  "sku": "<?= $product->id ?>",
  "brand": { "@type": "Brand", "name": "Emetix" },
  "offers": {
    "@type": "Offer",
    "url": "<?= current_url() ?>",
    "priceCurrency": "MXN",
    "price": "<?= $product->price ?>",
    "availability": "<?= ($product->stock > 0) ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' ?>",
    "seller": { "@type": "Organization", "name": "Emetix" }
  }
}
</script>
```

### D. Archivo `robots.txt`
Creado en `public/robots.txt` para bloquear carpetas del sistema.

```text
User-agent: *
Allow: /
Disallow: /application/
Disallow: /system/
Disallow: /admin/
Sitemap: http://localhost/ecomerce/sitemap.xml
```

---

## 3. Recomendaciones de Seguridad
*   **XSS (Cross-Site Scripting)**: Siempre usamos `html_escape()` al imprimir datos de usuario en meta tags. Esto evita que scripts maliciosos se ejecuten al compartir el enlace.
*   **Validación JSON:** Asegurar que las comillas dobles `"` dentro de las descripciones se escapen con `\"` para no romper el formato JSON-LD.
