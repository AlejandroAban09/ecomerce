<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Product_model $Product_model
 */
class Home extends CI_Controller
{
    public function index()
    {
        $this->load->model('Product_model');

        // Obtener los productos más recientes
        $data['latest_products'] = $this->Product_model->get_all_active_products(10);

        // SEO Info Home
        $data['meta_title'] = 'Emetix - Tu tienda de electrónica de confianza';
        $data['meta_description'] = 'Encuentra los mejores productos de electrónica, audio y tecnología con las mejores ofertas en Emetix.';
        $data['meta_keywords'] = 'emetix, electronica, tienda online, audifonos, bocinas';

        $this->load->view('home', $data);
    }
}
