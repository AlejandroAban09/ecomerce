<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Cloudinary\Configuration\Configuration;

class Cloudinary_lib {

    public function __construct() {
        // Reemplaza esto con TUS credenciales de Cloudinary
        Configuration::instance([
            'cloud' => [
                'cloud_name' => 'dtagmlesj', 
                'api_key'    => '673535743796357', 
                'api_secret' => 'phF9LOXe0pAAuXaUGazlH4NvktU'],
            'url' => [
                'secure' => true]]);
    }
}