<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Product_model $Product_model
 * @property CI_Input $input
 * @property CI_Pagination $pagination
 */
class Shop extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Product_model');
        $this->load->library('pagination');
    }

    public function index($offset = 0)
    {
        // Parámetros de búsqueda
        $search = $this->input->get('q');

        // Configuración de paginación
        $config['base_url'] = base_url('shop/index');
        $config['total_rows'] = $this->Product_model->count_all_active_products($search);
        $config['per_page'] = 12; // Productos por página
        $config['uri_segment'] = 3;
        $config['reuse_query_string'] = TRUE; // Para mantener la búsqueda al cambiar de página

        // Estilos de Bootstrap 5 para la paginación
        $config['full_tag_open'] = '<nav><ul class="pagination justify-content-center">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['attributes'] = ['class' => 'page-link'];
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close'] = '</span></li>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';

        $this->pagination->initialize($config);

        $data['products'] = $this->Product_model->get_all_active_products($config['per_page'], $offset, $search);
        $data['pagination'] = $this->pagination->create_links();
        $data['search'] = $search;

        $this->load->view('shop/index', $data);
    }
}
