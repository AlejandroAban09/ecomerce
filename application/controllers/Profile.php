<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_Session $session
 * @property User_model $User_model
 * @property Seller_model $Seller_model
 * @property Order_model $Order_model
 * @property CI_Form_validation $form_validation
 * @property CI_Input $input
 */
class Profile extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // Verificar si el usuario está logueado, si no, redirigir
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        $this->load->model('User_model');
        $this->load->model('Seller_model');
        $this->load->model('Order_model'); // Cargar modelo de Ordenes
        $this->load->library('form_validation'); // Cargar librería de validación
    }

    public function index()
    {
        $user_id = $this->session->userdata('user_id');

        $data['user'] = $this->User_model->get_user_by_id($user_id);
        $data['seller'] = $this->Seller_model->get_seller_profile($user_id);
        $data['orders'] = $this->Order_model->get_orders_by_user($user_id); // Obtener historial de compras

        $this->load->view('profile/index', $data);
    }

    // Ver detalle de orden
    public function view_order($order_id)
    {
        $user_id = $this->session->userdata('user_id');
        $data['user'] = $this->User_model->get_user_by_id($user_id);
        $data['order'] = $this->Order_model->get_order_with_details($order_id, $user_id);
        $data['current_page'] = 'orders'; // Para resaltar en el sidebar

        if (!$data['order']) {
            show_404();
        }

        $this->load->view('profile/order_detail', $data);
    }

    // Activar tienda (convertirse en vendedor)
    public function become_seller()
    {
        $this->form_validation->set_rules('store_name', 'Nombre de la Tienda', 'required|min_length[3]|is_unique[seller_profiles.store_name]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('profile');
        } else {
            $store_name = $this->input->post('store_name');
            $user_id = $this->session->userdata('user_id');

            // Crear slug simple
            $slug = url_title($store_name, '-', TRUE);

            $data = array(
                'user_id' => $user_id,
                'store_name' => $store_name,
                'store_slug' => $slug
            );

            if ($this->Seller_model->create_store($data)) {
                // Actualizar rol en sesión si es necesario, o solo flag 'is_seller'
                // Por ahora solo actualizamos la tabla
                $this->User_model->update_seller_status($user_id, 1);

                $this->session->set_flashdata('success', '¡Felicidades! Tu tienda ha sido creada.');
            } else {
                $this->session->set_flashdata('error', 'Error al crear la tienda.');
            }
            redirect('profile');
        }
    }
}
