<?php

class Modulo extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function guardar($nombre, $id = null) {

//        $this->db->select('id, nombre');
//        $this->db->from('modulo');
//        $consulta = $this->db->get();
//        $resultado = $consulta->result();
        
        

        $datos = array(
            'nombre' => $nombre,
        );
        if ($id) {
            $this->db->where('id', $id);
            $this->db->update('modulo', $datos);
        } else {
            $this->db->insert('modulo', $datos);
        }
    }

}
