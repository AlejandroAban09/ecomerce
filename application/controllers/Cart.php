<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Product_model $Product_model
 * @property CI_Cart $cart
 * @property CI_Input $input
 * @property CI_Session $session
 * @property CI_User_agent $agent
 */
class Cart extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('user_agent');
        $this->load->model('Product_model');
    }

    // Mostrar el carrito
    public function index()
    {
        $this->load->view('cart/index');
    }

    // Agregar ítem al carrito
    public function add()
    {
        $product_id = $this->input->post('product_id');
        $qty = $this->input->post('qty');

        if (!$product_id || !$qty) {
            if ($this->input->is_ajax_request()) {
                header('Content-Type: application/json');
                echo json_encode(['status' => 'error', 'message' => 'Datos incompletos.']);
                return;
            }
            redirect('shop');
        }

        $product = $this->Product_model->get_product_by_id($product_id);

        if (!$product) {
            if ($this->input->is_ajax_request()) {
                header('Content-Type: application/json');
                echo json_encode(['status' => 'error', 'message' => 'Producto no encontrado.']);
                return;
            }
            show_404();
        }

        // Configuración para la librería Cart de CI
        $data = array(
            'id'      => $product->id,
            'qty'     => $qty,
            'price'   => $product->price,
            'name'    => $product->name, // El nombre no debe tener caracteres especiales, CI Cart es estricto a veces
            'options' => array(
                'image' => $product->image_url,
                'slug' => $product->slug
            )
        );

        // Limpiamos nombre para evitar problemas con la librería Cart antigua
        $data['name'] = preg_replace('/[^a-zA-Z0-9 ]/', '', $data['name']);

        $this->cart->insert($data);

        $this->save_cart_to_cookie(); // <-- Guardar en Cookie

        if ($this->input->is_ajax_request()) {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'success',
                'message' => 'Producto agregado al carrito.',
                'cart_count' => $this->cart->total_items()
            ]);
            return;
        }

        $this->session->set_flashdata('success', 'Producto agregado al carrito.');
        redirect($this->agent->referrer() ?: 'shop');
    }

    // Actualizar carrito
    public function update()
    {
        $data = $this->input->post();

        if (!empty($data['cart'])) {
            $update_data = [];
            foreach ($data['cart'] as $rowid => $qty) {
                $update_data[] = array(
                    'rowid' => $rowid,
                    'qty'   => $qty
                );
            }
            $this->cart->update($update_data);

            $this->save_cart_to_cookie(); // <-- Guardar en Cookie

            $this->session->set_flashdata('success', 'Carrito actualizado.');
        }

        redirect('cart');
    }

    // Eliminar ítem
    public function remove($rowid)
    {
        $this->cart->remove($rowid);

        $this->save_cart_to_cookie(); // <-- Guardar en Cookie

        $this->session->set_flashdata('success', 'Producto eliminado del carrito.');
        redirect('cart');
    }

    /**
     * Guarda el contenido actual del carrito en una cookie cifrada (o base64 simple)
     * para recuperarlo en futuras sesiones de invitado.
     */
    private function save_cart_to_cookie()
    {
        $cart_content = $this->cart->contents();
        $simplified_cart = [];

        foreach ($cart_content as $item) {
            $simplified_cart[] = [
                'id' => $item['id'],
                'qty' => $item['qty']
            ];
        }

        // Si hay items, guardamos cookie por 30 días
        if (!empty($simplified_cart)) {
            $json_cart = json_encode($simplified_cart);
            // Base64 para evitar caracteres raros en la cookie
            $encoded_cart = base64_encode($json_cart);
            set_cookie('guest_cart', $encoded_cart, 2592000);
        } else {
            // Si está vacío, borramos la cookie
            delete_cookie('guest_cart');
        }
    }
}
