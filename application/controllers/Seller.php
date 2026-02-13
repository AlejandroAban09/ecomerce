<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Cloudinary\Api\Upload\UploadApi;

/**
 * @property CI_Session $session
 * @property CI_Input $input
 * @property CI_Form_validation $form_validation
 * @property User_model $User_model
 * @property Product_model $Product_model
 * @property Order_model $Order_model
 */
class Seller extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // Solo acceso a usuarios logueados y que sean vendedores
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }

        // Verificar si es vendedor (podemos checar la sesión o la BD, usaremos lo que hay en sesión que actualizamos en Profile)
        // Nota: En un caso real user_model->get_user_by_id es más seguro por si le quitaron el permiso mientras navegaba
        // Pero por eficiencia usaremos la sesión por ahora, asumiendo que el usuario relogueó o actualizamos sesión.
        // O mejor, consultamos rápido.
        $this->load->model('User_model');
        $user = $this->User_model->get_user_by_id($this->session->userdata('user_id'));

        if (!$user->is_seller) {
            $this->session->set_flashdata('error', 'Debes activar tu tienda para acceder a esta sección.');
            redirect('profile');
        }

        $this->load->model('Product_model');
        $this->load->model('Order_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $user_id = $this->session->userdata('user_id');
        $data['user'] = $this->User_model->get_user_by_id($user_id);
        $data['current_page'] = 'seller_dashboard';
        $data['products'] = $this->Product_model->get_products_by_seller($user_id);

        $this->load->view('seller/dashboard', $data);
    }

    // Ver reporte de ventas
    public function sales()
    {
        $user_id = $this->session->userdata('user_id');
        // Datos para el layout
        $data['user'] = $this->User_model->get_user_by_id($user_id);
        $data['current_page'] = 'sales';
        $data['page_title'] = 'Reporte de Ventas';

        // Datos específicos de la vista
        $data['sales'] = $this->Order_model->get_sales_by_seller($user_id);

        // Vista de contenido (solo la parte central, sin header/footer)
        $data['content_view'] = 'seller/sales_content';

        // Cargar template principal
        $this->load->view('layout/dashboard_template', $data);
    }

    public function create_product()
    {
        $user_id = $this->session->userdata('user_id');
        $data['user'] = $this->User_model->get_user_by_id($user_id);
        $data['current_page'] = 'create_product'; // O 'seller_dashboard' si prefieres no tener un item nuevo

        $this->form_validation->set_rules('name', 'Nombre del Producto', 'required|min_length[5]');
        $this->form_validation->set_rules('price', 'Precio', 'required|numeric|greater_than[0]');
        $this->form_validation->set_rules('stock', 'Stock', 'required|integer|greater_than_equal_to[0]');
        $this->form_validation->set_rules('description', 'Descripción', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('seller/create_product', $data);
        } else {
            // Procesar el formulario
            $name = $this->input->post('name');
            $slug = url_title($name, '-', TRUE) . '-' . time(); // Slug único básico

            $product_data = array(
                'seller_id' => $user_id,
                'name' => $name,
                'slug' => $slug,
                'description' => $this->input->post('description'),
                'price' => $this->input->post('price'),
                'stock' => $this->input->post('stock'),
                'status' => 'active' // Activo por defecto para este MVP
            );

            // Intentar subir imagen si existe
            if (!empty($_FILES['image']['name'])) {
                try {
                    // Subir a Cloudinary
                    $upload = (new UploadApi())->upload($_FILES['image']['tmp_name'], [
                        'folder' => 'ecomerce_productos',
                        'resource_type' => 'image'
                    ]);

                    // Insertar producto
                    $product_id = $this->Product_model->create_product($product_data);

                    // Insertar imagen
                    $image_data = array(
                        'product_id' => $product_id,
                        'image_url' => $upload['secure_url'],
                        'public_id' => $upload['public_id'],
                        'is_main' => 1
                    );
                    $this->Product_model->add_product_image($image_data);

                    $this->session->set_flashdata('success', 'Producto publicado exitosamente.');
                    redirect('seller');
                } catch (Exception $e) {
                    $this->session->set_flashdata('error', 'Error al subir la imagen: ' . $e->getMessage());
                    $this->load->view('seller/create_product', $data); // Recargar vista con error
                }
            } else {
                $this->session->set_flashdata('error', 'La imagen es obligatoria.');
                $this->load->view('seller/create_product', $data);
            }
        }
    }
    public function edit_product($id)
    {
        $user_id = $this->session->userdata('user_id');
        $data['user'] = $this->User_model->get_user_by_id($user_id);
        $data['current_page'] = 'seller_dashboard';
        $product = $this->Product_model->get_product_by_id($id);

        if (!$product || $product->seller_id != $user_id) {
            $this->session->set_flashdata('error', 'Producto no encontrado o no autorizado.');
            redirect('seller');
        }

        // Reglas de validación
        $this->form_validation->set_rules('name', 'Nombre del Producto', 'required|min_length[5]');
        $this->form_validation->set_rules('price', 'Precio', 'required|numeric|greater_than[0]');
        $this->form_validation->set_rules('stock', 'Stock', 'required|integer|greater_than_equal_to[0]');
        $this->form_validation->set_rules('description', 'Descripción', 'required');

        if ($this->form_validation->run() === FALSE) {
            $data['product'] = $product;
            $this->load->view('seller/edit_product', $data);
        } else {
            // Actualizar datos básicos
            $update_data = array(
                'name' => $this->input->post('name'),
                'description' => $this->input->post('description'),
                'price' => $this->input->post('price'),
                'stock' => $this->input->post('stock'),
                'status' => $this->input->post('status')
            );

            // Si se cambia el nombre, podríamos actualizar el slug, pero mejor dejarlo fijo para SEO por ahora o actualizarlo si quieres.
            // $update_data['slug'] = url_title($this->input->post('name'), '-', TRUE) . '-' . time();

            // Manejo de imagen opcional
            if (!empty($_FILES['image']['name'])) {
                try {
                    $upload = (new UploadApi())->upload($_FILES['image']['tmp_name'], [
                        'folder' => 'ecomerce_productos',
                        'resource_type' => 'image'
                    ]);

                    $image_data = array(
                        'image_url' => $upload['secure_url'],
                        'public_id' => $upload['public_id']
                    );
                    $this->Product_model->update_main_image($id, $image_data);
                } catch (Exception $e) {
                    $this->session->set_flashdata('error', 'Error al subir la nueva imagen: ' . $e->getMessage());
                    redirect('seller/edit_product/' . $id);
                    return;
                }
            }

            if ($this->Product_model->update_product($id, $update_data)) {
                $this->session->set_flashdata('success', 'Producto actualizado correctamente.');
            } else {
                $this->session->set_flashdata('error', 'No se pudieron guardar los cambios.');
            }
            redirect('seller');
        }
    }

    public function delete_product($id)
    {
        $user_id = $this->session->userdata('user_id');
        $product = $this->Product_model->get_product_by_id($id);

        if (!$product || $product->seller_id != $user_id) {
            $this->session->set_flashdata('error', 'No autorizado.');
            redirect('seller');
        }

        // Aquí podríamos borrar la imagen de Cloudinary también si guardáramos el public_id y tuvieramos la librería configurada para destroy
        // Por ahora borramos de BD
        $this->Product_model->delete_product($id);
        $this->session->set_flashdata('success', 'Producto eliminado.');
        redirect('seller');
    }
}
