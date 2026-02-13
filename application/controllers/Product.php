<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Product_model $Product_model
 */
class Product extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Product_model');
    }

    public function index($slug = NULL)
    {
        if (!$slug) {
            redirect('shop'); // O home
        }

        $data['product'] = $this->Product_model->get_product_by_slug($slug);

        if (!$data['product']) {
            show_404();
        }

        // SEO Data
        $data['meta_title'] = $data['product']->name . ' | Emetix';
        $data['meta_description'] = substr(strip_tags($data['product']->description), 0, 160) . '...';
        $data['meta_keywords'] = 'comprar ' . $data['product']->name . ', precio ' . $data['product']->name . ', electronica, oferta';
        $data['og_image'] = $data['product']->image_url;

        // --- COOKIES: Vistos Recientemente ---
        $cookie_name = 'recently_viewed';
        $current_id = $data['product']->id;
        $viewed_ids = [];

        // 1. Obtener cookie actual
        if (get_cookie($cookie_name)) {
            $viewed_ids = explode(',', get_cookie($cookie_name));
        }

        // 2. Agregar ID actual si no existe (al principio)
        if (!in_array($current_id, $viewed_ids)) {
            array_unshift($viewed_ids, $current_id);
        } else {
            // Si ya existe, lo movemos al principio
            $key = array_search($current_id, $viewed_ids);
            unset($viewed_ids[$key]);
            array_unshift($viewed_ids, $current_id);
        }

        // 3. Limitar a 5 productos
        $viewed_ids = array_slice($viewed_ids, 0, 5);

        // 4. Guardar cookie (Expira en 30 días)
        set_cookie($cookie_name, implode(',', $viewed_ids), 2592000);

        // 5. Obtener los productos para mostrar en la vista (excluyendo el actual si se desea, o mostrandolos todos)
        // Quitamos el producto actual de la lista "Vistos" para que no se repita en el slider de abajo
        $ids_to_fetch = array_diff($viewed_ids, [$current_id]);

        $data['recently_viewed'] = [];
        if (!empty($ids_to_fetch)) {
            $data['recently_viewed'] = $this->Product_model->get_products_by_ids($ids_to_fetch);
        }
        // -------------------------------------

        $this->load->view('product/detail', $data);
    }
}
