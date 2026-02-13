-- CRACION DE LA BASE DE DATOS
CREATE DATABASE IF NOT EXISTS ecomerce_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ecomerce_db;

-- 1. USUARIOS Y AUTENTICACIÓN
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, -- Hash de contraseña (Bcrypt/Argon2)
    full_name VARCHAR(100),
    phone VARCHAR(20),
    role ENUM('admin', 'user') DEFAULT 'user', -- Admin del sistema vs Usuario normal
    is_seller BOOLEAN DEFAULT FALSE, -- Si el usuario también puede vender
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    status TINYINT DEFAULT 1 -- 1: Activo, 0: Banned/Inactivo
) ENGINE=InnoDB;

-- Perfil de Vendedor (Tienda)
-- Información extra si el usuario activa el "Modo Vendedor"
CREATE TABLE seller_profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    store_name VARCHAR(100) NOT NULL,
    store_slug VARCHAR(100) NOT NULL UNIQUE, -- Para URLs amigables: mi-tienda
    description TEXT,
    logo_url VARCHAR(255), -- Cloudinary
    banner_url VARCHAR(255), -- Cloudinary
    bank_info TEXT, -- Información encriptada o referencia para pagos
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Direcciones de Usuario (Envío/Facturación)
CREATE TABLE user_addresses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    alias VARCHAR(50), -- "Casa", "Oficina"
    recipient_name VARCHAR(100),
    phone VARCHAR(20),
    street_address TEXT NOT NULL,
    city VARCHAR(100) NOT NULL,
    state VARCHAR(100) NOT NULL,
    postal_code VARCHAR(20),
    country VARCHAR(50) DEFAULT 'Mexico',
    is_default BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Sesiones de CodeIgniter (Opcional, si usamos driver 'database')
CREATE TABLE ci_sessions (
    id VARCHAR(128) NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    timestamp INT(10) unsigned DEFAULT 0 NOT NULL,
    data BLOB NOT NULL,
    KEY ci_sessions_timestamp (timestamp)
) ENGINE=InnoDB;

-- 2. PRODUCTOS Y CATÁLOGO
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    parent_id INT NULL, -- Para subcategorías (ej. Ropa > Hombre > Camisas)
    image_url VARCHAR(255),
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    seller_id INT NOT NULL, -- Quién vende este producto
    category_id INT NULL,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    stock INT DEFAULT 0, -- Stock simple. Para variantes se necesita otra tabla.
    status ENUM('active', 'draft', 'paused') DEFAULT 'draft',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    views_count INT DEFAULT 0, -- Para popularidad
    FOREIGN KEY (seller_id) REFERENCES users(id), -- Referencia a tabla users (el dueño de la tienda)
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
    INDEX (name), -- Indice para búsqueda simple
    FULLTEXT (name, description) -- Indice para buscador potente
) ENGINE=InnoDB;

-- Imágenes de Productos (Cloudinary)
CREATE TABLE product_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    public_id VARCHAR(100), -- ID de Cloudinary para facilitar borrado
    is_main BOOLEAN DEFAULT FALSE,
    sort_order INT DEFAULT 0,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 3. INTERACCIÓN Y VENTAS
-- Carrito de Compras (Persistente en BD)
CREATE TABLE carts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL, -- Null para usuarios invitados (guests) identificados por cookie/session
    session_id VARCHAR(128) NULL, -- Para ligar con ci_sessions si no hay login
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE cart_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cart_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT DEFAULT 1,
    added_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cart_id) REFERENCES carts(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Lista de Deseos (Wishlist)
CREATE TABLE wishlists (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    added_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(user_id, product_id), -- Prevenir duplicados
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Reseñas y Calificaciones
CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    user_id INT NOT NULL,
    rating TINYINT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 4. ORDENES Y PAGOS
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_number VARCHAR(20) NOT NULL UNIQUE, -- Ej. ORD-2023-0001
    user_id INT NOT NULL,
    shipping_address_id INT, -- Snapshot de la dirección en el momento de la compra? O referencia? Mejor snapshot en JSON o tablas separadas, pero por simpleza referencia.
    total_amount DECIMAL(10, 2) NOT NULL,
    status ENUM('pendiente', 'pagado', 'procesando', 'en_transito', 'completado', 'cancelado', 'rechazado', 'reembolsado') DEFAULT 'pendiente',
    payment_method VARCHAR(50), -- 'tarjeta', 'paypal', 'transferencia'
    payment_status VARCHAR(50) DEFAULT 'pendiente',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (shipping_address_id) REFERENCES user_addresses(id)
) ENGINE=InnoDB;

-- Detalles de la Orden
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT, -- Puede ser NULL si el producto se borra, pero queremos mantener el historial
    product_name VARCHAR(255) NOT NULL, -- Guardamos el nombre por si cambia
    price DECIMAL(10, 2) NOT NULL, -- Precio HISTÓRICO en el momento de la compra
    quantity INT NOT NULL,
    subtotal DECIMAL(10, 2) GENERATED ALWAYS AS (price * quantity) STORED,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Historial de Estados del Pedido (Trazabilidad)
CREATE TABLE order_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    status VARCHAR(50) NOT NULL,
    comment TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
) ENGINE=InnoDB;
