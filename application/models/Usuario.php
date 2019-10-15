<?php

class Usuario extends CI_Model {
    
    function validarcontraseña() {
        $usuario = $this->input->post("usuario");
        $contraseña = md5($this->input->post("contraseña"));
        
        return $this->db->get_where('usuario', array('usuario' => $usuario, 'contraseña' => $contraseña, 'estado' => 'A'))->row();
    }
    
    function validarusuario() {
        $usuario = $this->input->post("usuario");
        
        return $this->db->get_where('usuario', array('usuario' => $usuario))->row();
    }
    
    function validarusuarioestado() {
        $usuario = $this->input->post("usuario");
        
        return $this->db->get_where('usuario', array('usuario' => $usuario, 'estado' => 'A'))->row();
    }
}