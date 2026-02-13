<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Product_model $Product_model
 * @property CI_Cart $cart
 * @property CI_Session $session
 */
class Checkout extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Product_model');

        // Verificar si el usuario está logueado
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'Debes iniciar sesión para completar tu compra.');
            redirect('auth/login');
        }

        // Verificar si hay items en el carrito
        if ($this->cart->total_items() <= 0) {
            redirect('shop');
        }
    }

    public function index()
    {
        // Simular página de checkout (en un caso real, aquí iría el formulario de pago/envío)
        $data['cart_contents'] = $this->cart->contents();
        $data['total'] = $this->cart->total();

        $this->load->view('checkout/index', $data);
    }

    public function place_order()
    {
        // 1. Crear la Orden
        $order_data = array(
            'user_id' => $this->session->userdata('user_id'),
            'order_number' => 'ORD-' . date('Ymd') . '-' . mt_rand(1000, 9999), // Generar número único
            'total_amount' => $this->cart->total(),
            'status' => 'completado', // ENUM value must match DB definition ('pendiente', ..., 'completado')
            'created_at' => date('Y-m-d H:i:s')
        );

        $this->db->trans_start(); // Iniciar transacción

        $order_id = $this->Product_model->create_order($order_data);

        // 2. Insertar detalles y descontar stock
        foreach ($this->cart->contents() as $item) {
            $item_data = array(
                'order_id' => $order_id,
                'product_id' => $item['id'],
                'product_name' => $item['name'], // Nombre del campo en BD
                'quantity' => $item['qty'],
                'price' => $item['price']
            );

            $this->Product_model->create_order_item($item_data);

            // AQUÍ ocurre la magia: Descontamos del stock real
            $this->Product_model->decrease_stock($item['id'], $item['qty']);
        }

        $this->db->trans_complete(); // Finalizar transacción

        if ($this->db->trans_status() === FALSE) {
            // Si algo falló, revertir todo
            $this->session->set_flashdata('error', 'Hubo un error al procesar tu orden. Inténtalo de nuevo.');
            redirect('checkout');
        } else {
            // Éxito
            $this->cart->destroy(); // Vaciar carrito
            $this->session->set_flashdata('success', '¡Gracias por tu compra! Tu orden #' . $order_id . ' ha sido confirmada.');
            redirect('shop'); // O redirigir a una página de 'Success'
        }
    }
}
