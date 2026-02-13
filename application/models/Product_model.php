<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Product_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    // Crear un nuevo producto
    public function create_product($data)
    {
        $this->db->insert('products', $data);
        return $this->db->insert_id();
    }

    // Guardar imagen del producto
    public function add_product_image($data)
    {
        return $this->db->insert('product_images', $data);
    }

    // Obtener productos de un vendedor específico
    public function get_products_by_seller($seller_id)
    {
        $this->db->select('products.*, product_images.image_url');
        $this->db->from('products');
        // Unir con la imagen principal (o cualquiera si no hay principal definida)
        $this->db->join('product_images', 'product_images.product_id = products.id AND product_images.is_main = 1', 'left');
        $this->db->where('products.seller_id', $seller_id);
        $this->db->order_by('products.created_at', 'DESC');
        return $this->db->get()->result();
    }

    // Obtener todos los productos (para el Home/Tienda) con opción de búsqueda y paginación
    public function get_all_active_products($limit = 20, $offset = 0, $search = null)
    {
        $this->db->select('products.*, product_images.image_url');
        $this->db->from('products');
        $this->db->join('product_images', 'product_images.product_id = products.id AND product_images.is_main = 1', 'left');
        $this->db->where('products.status', 'active');

        if ($search) {
            $this->db->group_start();
            $this->db->like('products.name', $search);
            $this->db->or_like('products.description', $search);
            $this->db->group_end();
        }

        $this->db->limit($limit, $offset);
        $this->db->order_by('products.created_at', 'DESC');
        return $this->db->get()->result();
    }

    // Contar total para paginación
    public function count_all_active_products($search = null)
    {
        $this->db->from('products');
        $this->db->where('status', 'active');

        if ($search) {
            $this->db->group_start();
            $this->db->like('name', $search);
            $this->db->or_like('description', $search);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }
    // Obtener un solo producto por ID
    public function get_product_by_id($product_id)
    {
        $this->db->select('products.*, product_images.image_url, product_images.public_id');
        $this->db->from('products');
        $this->db->join('product_images', 'product_images.product_id = products.id AND product_images.is_main = 1', 'left');
        $this->db->where('products.id', $product_id);
        return $this->db->get()->row();
    }

    // Actualizar producto
    public function update_product($product_id, $data)
    {
        $this->db->where('id', $product_id);
        return $this->db->update('products', $data);
    }

    // Eliminar producto
    public function delete_product($product_id)
    {
        $this->db->where('id', $product_id);
        return $this->db->delete('products');
    }
    // Actualizar imagen principal
    public function update_main_image($product_id, $image_data)
    {
        // Primero intentamos actualizar si ya existe una main
        $this->db->where('product_id', $product_id);
        $this->db->where('is_main', 1);
        $q = $this->db->get('product_images');

        if ($q->num_rows() > 0) {
            $this->db->where('product_id', $product_id);
            $this->db->where('is_main', 1);
            return $this->db->update('product_images', $image_data);
        } else {
            // Si no habia, insertamos
            $image_data['product_id'] = $product_id;
            $image_data['is_main'] = 1;
            return $this->db->insert('product_images', $image_data);
        }
    }
    // Obtener producto por slug
    public function get_product_by_slug($slug)
    {
        $this->db->select('products.*, product_images.image_url, product_images.public_id, users.username as seller_name, seller_profiles.store_name');
        $this->db->from('products');
        $this->db->join('product_images', 'product_images.product_id = products.id AND product_images.is_main = 1', 'left');
        $this->db->join('users', 'users.id = products.seller_id');
        $this->db->join('seller_profiles', 'seller_profiles.user_id = products.seller_id', 'left');
        $this->db->where('products.slug', $slug);
        return $this->db->get()->row();
    }
    // Descontar stock (al finalizar compra)
    public function decrease_stock($product_id, $qty)
    {
        $this->db->set('stock', 'stock - ' . (int)$qty, FALSE);
        $this->db->where('id', $product_id);
        return $this->db->update('products');
    }

    // Crear orden
    public function create_order($data)
    {
        $this->db->insert('orders', $data);
        return $this->db->insert_id();
    }

    // Crear detalle de orden
    public function create_order_item($data)
    {
        return $this->db->insert('order_items', $data);
    }

    // Obtener productos por lista de IDs (para Vistos Recientemente)
    public function get_products_by_ids($ids)
    {
        if (empty($ids)) {
            return [];
        }
        $this->db->select('products.*, product_images.image_url');
        $this->db->from('products');
        $this->db->join('product_images', 'product_images.product_id = products.id AND product_images.is_main = 1', 'left');
        $this->db->where_in('products.id', $ids);
        return $this->db->get()->result();
    }
}
