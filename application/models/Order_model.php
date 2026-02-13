<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Order_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // Obtener órdenes de un usuario (Historial de Compras)
    public function get_orders_by_user($user_id)
    {
        $this->db->where('user_id', $user_id);
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('orders')->result();
    }

    // Obtener una orden específica con sus items
    public function get_order_with_details($order_id, $user_id = null)
    {
        // Info de la orden
        $this->db->where('id', $order_id);

        // Si se pasa user_id, verificamos que le pertenezca (seguridad)
        if ($user_id) {
            $this->db->where('user_id', $user_id);
        }

        $order = $this->db->get('orders')->row();

        if (!$order) return null;

        // Items de la orden
        $this->db->select('order_items.*, product_images.image_url as product_image'); // Podríamos unirlos con products para la foto actual
        $this->db->from('order_items');
        $this->db->join('product_images', 'product_images.product_id = order_items.product_id AND product_images.is_main = 1', 'left'); // Foto
        $this->db->join('products', 'products.id = order_items.product_id', 'left'); // Join para imagen en caso de que order_items no tenga foto guardada
        $this->db->where('order_items.order_id', $order_id);
        $items = $this->db->get()->result();

        $order->items = $items;
        return $order;
    }

    // Obtener ventas de un vendedor (Items vendidos por él)
    public function get_sales_by_seller($seller_id)
    {
        $this->db->select('
            order_items.id as item_id,
            order_items.product_name,
            order_items.quantity,
            order_items.price,
            order_items.subtotal,
            orders.order_number,
            orders.created_at as sale_date,
            orders.status as order_status,
            users.username as buyer_name,
            product_images.image_url as product_image,
        ');
        $this->db->from('order_items');
        $this->db->join('orders', 'orders.id = order_items.order_id');
        $this->db->join('products', 'products.id = order_items.product_id');
        // Unimos con product_images para la foto
        $this->db->join('product_images', 'product_images.product_id = products.id AND product_images.is_main = 1', 'left');
        $this->db->join('users', 'users.id = orders.user_id'); // Comprador

        $this->db->where('products.seller_id', $seller_id); // FILTRO CLAVE: Solo productos de este vendedor
        $this->db->order_by('orders.created_at', 'DESC');

        return $this->db->get()->result();
    }
}
