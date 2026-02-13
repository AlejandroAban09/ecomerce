<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Cloudinary\Api\Upload\UploadApi;

class TestCloudinary extends CI_Controller
{

    public function index()
    {
        echo "<h1>Prueba de Conexión Cloudinary</h1>";

        try {
            // 1. Cargar configuración (esto inicializa la instancia de Configuration)
            $this->load->library('cloudinary_lib');

            echo "Librería cargada...<br>";

            // 2. Intentar subir una imagen desde una URL remota de prueba
            // Usamos una imagen pequeña de Wikipedia como prueba
            $sample_image_url = "https://dummyimage.com/300x200/000/fff.png&text=Test+Image";

            echo "Intentando subir imagen de prueba...<br>";

            $upload = (new UploadApi())->upload($sample_image_url, [
                'folder' => 'test_uploads',
                'public_id' => 'prueba_conexion_' . time()
            ]);

            // 3. Mostrar éxito
            echo "<h3 style='color:green'>¡ÉXITO! Conexión correcta.</h3>";
            echo "Detalles de la imagen subida:<br>";
            echo "<pre>";
            print_r($upload);
            echo "</pre>";

            echo "<img src='" . $upload['secure_url'] . "' alt='Imagen subida' />";
        } catch (Exception $e) {
            echo "<h3 style='color:red'>ERROR: No se pudo conectar.</h3>";
            echo "Mensaje: " . $e->getMessage();
        }
    }
}
