# Guia de Implementación de Cookies ("Visto Recientemente") en CodeIgniter 3

Esta guía documenta la implementación técnica de la funcionalidad **"Productos Vistos Recientemente"** basada en **Cookies** del navegador. Esta funcionalidad mejora la UX permitiendo al usuario ver rápidamente los últimos 5 productos que ha visitado.

## 1. Resumen Técnico
Se basa en cookies persistentes (30 días) creadas y leídas por el controlador `Product.php`.
1.  **Helper (`autoload.php`)**: Habilitación del manejo de cookies global.
2.  **Controlador (`Product.php`)**: Lógica para agregar IDs de productos a una lista separada por comas.
3.  **Modelo (`Product_model.php`)**: Método para obtener productos masivamente por ID (`WHERE IN`).
4.  **Vista (`detail.php`)**: Visualización de productos relacionados.

---

## 2. Detalle de Implementación

### A. Configuración Global: `application/config/autoload.php`
Se agregó el helper `cookie` al array de carga automática para tener acceso a `set_cookie()` y `get_cookie()` en cualquier parte del sistema.

```php
$autoload['helper'] = array('url', 'form', 'cookie');
```

---

### B. Lógica del Controlador: `application/controllers/Product.php`
En el método `index()`, se gestiona la cookie `recently_viewed`.

**Lógica Implementada:**
1.  **Lectura:** Recupera la cookie actual. Si existe, explota el string (`explode`) para obtener un array de IDs.
2.  **Validación y Orden:**
    *   Si el producto actual ya está en la lista, lo elimina de su posición anterior.
    *   Agrega el producto actual al **principio** del array (`array_unshift`).
3.  **Límite:** Mantiene solo los primeros 5 elementos (`array_slice`).
4.  **Escritura:** Convierte el array a string (`implode`) y guarda la cookie por 30 días (`2592000` segundos).
5.  **Consulta:** Obtiene los datos de los productos *restantes* (excluyendo el actual) para mostrarlos.

**Código Clave:**
```php
// --- COOKIES: Vistos Recientemente ---
$cookie_name = 'recently_viewed';
$current_id = $data['product']->id;
$viewed_ids = [];

// 1. Obtener cookie
if (get_cookie($cookie_name)) {
    $viewed_ids = explode(',', get_cookie($cookie_name));
}

// 2. Lógica de agregar/mover
if (!in_array($current_id, $viewed_ids)) {
    array_unshift($viewed_ids, $current_id);
} else {
    $key = array_search($current_id, $viewed_ids);
    unset($viewed_ids[$key]);
    array_unshift($viewed_ids, $current_id);
}
// 3. Limitar a 5
$viewed_ids = array_slice($viewed_ids, 0, 5);

// 4. Guardar (30 días)
set_cookie($cookie_name, implode(',', $viewed_ids), 2592000);

// 5. Obtener datos para la vista
$ids_to_fetch = array_diff($viewed_ids, [$current_id]);
if (!empty($ids_to_fetch)) {
     $data['recently_viewed'] = $this->Product_model->get_products_by_ids($ids_to_fetch);
}
```

---

### C. Modelo de Datos: `application/models/Product_model.php`
Se creó el método `get_products_by_ids($ids)` para realizar una consulta optimizada.

**Código Implementado:**
```php
public function get_products_by_ids($ids)
{
    if (empty($ids)) return [];
    
    $this->db->select('products.*, product_images.image_url');
    // ... joins ...
    $this->db->where_in('products.id', $ids); // Optimización: WHERE IN
    return $this->db->get()->result();
}
```

---

### D. Visualización: `application/views/product/detail.php`
Se agregó una sección al final del contenedor principal. Itera sobre `$data['recently_viewed']` y genera tarjetas simples.

**Código (simplificado):**
```php
<?php if (!empty($recently_viewed)): ?>
<div class="recently-viewed">
    <h3>Visto Recientemente</h3>
    <div class="row">
        <?php foreach ($recently_viewed as $rv_product): ?>
            <!-- Card del producto -->
             <a href="<?= base_url('product/' . $rv_product->slug) ?>">
                <img src="<?= $rv_product->image_url ?>">
                <h6><?= $rv_product->name ?></h6>
                <span>$<?= $rv_product->price ?></span>
             </a>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>
```

---

## 3. Funcionalidad: Carrito Persistente (Guest Cart)

Esta funcionalidad permite que el carrito de compras de un usuario **no logueado** sobreviva al cierre del navegador y se fusione con su cuenta cuando inicie sesión.

### 3.1 Guardado en Cookie (Cart Controller)

**Archivo:** `application/controllers/Cart.php`
**Método Privado:** `save_cart_to_cookie()`

Cada vez que se modifica el carrito (`add`, `update`, `remove`), se llama a este método.

**Lógica:**
1.  Obtiene el contenido actual del carrito (`$this->cart->contents()`).
2.  Crea una versión simplificada (array) solo con `id` y `qty`.
3.  Convierte a JSON y **codifica en Base64** para evitar problemas de caracteres en la cookie.
4.  Guarda la cookie `guest_cart` por 30 días.
5.  Si el carrito se vacía, borra la cookie.

```php
private function save_cart_to_cookie()
{
    // ... simplificación del array ...
    $json_cart = json_encode($simplified_cart);
    $encoded_cart = base64_encode($json_cart); 
    set_cookie('guest_cart', $encoded_cart, 2592000); 
}
```

### 3.2 Fusión al Iniciar Sesión (Auth Controller)

**Archivo:** `application/controllers/Auth.php`
**Método:** `login()`

Cuando el usuario se loguea exitosamente, el sistema busca la cookie `guest_cart`.

**Lógica:**
1.  Verifica si existe `get_cookie('guest_cart')`.
2.  Decodifica Base64 -> JSON -> Array.
3.  **Obtiene los items actuales del carrito de sesión**.
4.  Itera sobre los items de la cookie:
    *   **Verificación de Duplicados:** Si el ID del producto de la cookie *ya existe* en el carrito de sesión, **lo salta** para no duplicar cantidades.
    *   Si no existe, lo inserta.

```php
// --- MERGE GUEST CART ---
if (get_cookie('guest_cart')) {
    $guest_items = json_decode(base64_decode(get_cookie('guest_cart')), true);
    
    // Obtener carrito actual para evitar duplicados
    $current_cart = $this->cart->contents();
    $current_product_ids = array_column($current_cart, 'id');

    foreach ($guest_items as $item) {
        // Si el producto ya está en sesión, NO lo agregamos de nuevo
        if (in_array($item['id'], $current_product_ids)) {
            continue;
        }

        // ... insertar en carrito ...
        $this->cart->insert($data);
    }
}
```

---

## 3. Recomendaciones de Seguridad y Buenas Prácticas

### Lo que NO debs guardar en Cookies
*   **Datos Sensibles:** Jamás guardes precios ("$500"), nombres de usuario, emails o roles de administrador en una cookie plana. El usuario puede modificar el archivo de cookie en su navegador fácilmente.
*   **Contraseñas:** Prohibido terminantemente.

### Validación de Datos
*   **IDs Numéricos:** Aunque CodeIgniter sanitiza las consultas SQL, siempre asume que la cookie puede haber sido manipulada. En una aplicación más estricta, deberías validar que cada elemento de `$viewed_ids` sea un número entero antes de pasarlo al modelo.
    ```php
    $viewed_ids = array_filter($viewed_ids, 'is_numeric'); // Validación extra recomendada
    ```

### Privacidad (GDPR)
*   **Consentimiento:** Si el proyecto crece, considera implementar un banner de "Aceptar Cookies". Aunque las cookies de funcionalidad (como esta) suelen estar exentas, es una buena práctica informar al usuario.
