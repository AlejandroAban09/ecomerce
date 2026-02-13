<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property User_model $User_model
 * @property Product_model $Product_model
 * @property CI_Session $session
 * @property CI_Form_validation $form_validation
 * @property CI_Input $input
 * @property CI_Cart $cart
 */
class Auth extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library('form_validation');
    }

    public function login()
    {
        // Si ya está logueado, redirigir al home
        if ($this->session->userdata('logged_in')) {
            redirect('home');
        }

        // Reglas de validación
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Contraseña', 'required');

        if ($this->form_validation->run() === FALSE) {
            // Mostrar formulario con errores (si los hay)
            $this->load->view('auth/login');
        } else {
            // Intentar Login
            $email = $this->input->post('email');
            $password = $this->input->post('password');

            $user = $this->User_model->login($email, $password);

            if ($user) {
                // Crear sesión
                $session_data = array(
                    'user_id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'role' => $user->role,
                    'logged_in' => TRUE
                );
                $this->session->set_userdata($session_data);

                // --- MERGE GUEST CART (Fusionar Carrito de Invitado) ---
                if (get_cookie('guest_cart')) {
                    $json_cart = base64_decode(get_cookie('guest_cart'));
                    $guest_items = json_decode($json_cart, true);

                    if (!empty($guest_items)) {
                        $this->load->library('cart');
                        $this->load->model('Product_model');

                        // Obtener contenido actual del carrito de sesión para verificar duplicados
                        $current_cart = $this->cart->contents();
                        $current_product_ids = [];
                        foreach ($current_cart as $param) {
                            $current_product_ids[] = $param['id'];
                        }

                        foreach ($guest_items as $item) {
                            // Si el producto YA está en el carrito de sesión, lo saltamos para no duplicar
                            if (in_array($item['id'], $current_product_ids)) {
                                continue;
                            }

                            $product = $this->Product_model->get_product_by_id($item['id']);
                            if ($product) {
                                $data = array(
                                    'id'      => $product->id,
                                    'qty'     => $item['qty'],
                                    'price'   => $product->price,
                                    'name'    => preg_replace('/[^a-zA-Z0-9 ]/', '', $product->name),
                                    'options' => array(
                                        'image' => $product->image_url,
                                        'slug' => $product->slug
                                    )
                                );
                                $this->cart->insert($data);
                            }
                        }
                    }
                    // Opcional: Borrar cookie después de fusionar (o mantenerla de respaldo)
                    // delete_cookie('guest_cart');
                }
                // -------------------------------------------------------

                // Mensaje de éxito
                $this->session->set_flashdata('success', '¡Bienvenido ' . $user->username . '!');
                redirect('home');
            } else {
                // Error de login
                $this->session->set_flashdata('error', 'Email o contraseña incorrectos.');
                redirect('auth/login');
            }
        }
    }

    public function register()
    {
        // Si ya está logueado, redirigir
        if ($this->session->userdata('logged_in')) {
            redirect('home');
        }

        $this->form_validation->set_rules('username', 'Usuario', 'required|min_length[3]|is_unique[users.username]', [
            'is_unique' => 'Este nombre de usuario ya está en uso.'
        ]);
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]', [
            'is_unique' => 'Este correo electrónico ya está registrado.'
        ]);
        $this->form_validation->set_rules('password', 'Contraseña', 'required|min_length[6]');
        $this->form_validation->set_rules('passconf', 'Confirmar Contraseña', 'required|matches[password]');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('auth/register');
        } else {
            // Preparar datos
            $data = array(
                'username' => $this->input->post('username'),
                'email' => $this->input->post('email'),
                'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                'role' => 'user'
            );

            if ($this->User_model->create_user($data)) {
                $this->session->set_flashdata('success', 'Cuenta creada exitosamente. Por favor inicia sesión.');
                redirect('auth/login');
            } else {
                $this->session->set_flashdata('error', 'Hubo un problema al crear tu cuenta. Intenta de nuevo.');
                redirect('auth/register');
            }
        }
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('auth/login');
    }
}
