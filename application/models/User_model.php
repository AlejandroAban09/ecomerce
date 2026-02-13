<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    // Registrar un nuevo usuario
    public function create_user($data)
    {
        $this->db->insert('users', $data);
        return $this->db->insert_id();
    }

    // Buscar usuario por email
    public function get_user_by_email($email)
    {
        $query = $this->db->get_where('users', array('email' => $email));
        return $query->row(); // Retorna un objeto o NULL
    }

    // Verificar login
    public function login($email, $password)
    {
        $user = $this->get_user_by_email($email);

        if ($user) {
            // Verificar contraseña hasheada
            if (password_verify($password, $user->password)) {

                // Quitar password del objeto por seguridad antes de retornarlo
                unset($user->password);
                return $user;
            }
        }
        return false;
    }

    // Verificar si existe el email (para validaciones)
    public function email_exists($email)
    {
        $this->db->where('email', $email);
        $query = $this->db->get('users');
        return $query->num_rows() > 0;
    }
    // Obtener usuario por ID
    public function get_user_by_id($id)
    {
        $query = $this->db->get_where('users', array('id' => $id));
        return $query->row();
    }

    // Actualizar estatus de vendedor
    public function update_seller_status($user_id, $status)
    {
        $this->db->where('id', $user_id);
        return $this->db->update('users', array('is_seller' => $status));
    }
}
