<?php

Class Sesion extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url_helper');
    }
    
    function cargarlocalesfisicos() {
        $accesos = $this->session->userdata('accesos');
        $acceso_locales_fisicos = $accesos["acceso_locales_fisicos"];
        
        $local_fisico = $this->session->userdata('local_fisico');
        $local_fisico_id = $local_fisico->id;
        $local_fisico_nombre = $local_fisico->nombre;
        
        $usuario = $this->session->userdata('usuario');
        
        $localesfisicos = array();
        
        foreach ($acceso_locales_fisicos as $fila) {
            $localesfisicos["localesFisicos"][] = array("id" => $fila->local_id, "nombre" => $fila->local);
        }
        
        $localesfisicos["localFisicoActual"] = array("id" => $local_fisico_id, "nombre" => $local_fisico_nombre);
        
        $localesfisicos["usuario"] = $usuario;
        
        echo json_encode($localesfisicos);
    }
    
    function cargarlocalesempresa() {
        
    }
    

    function cambiarlocalfisico() {
        $local_fisico_array = $this->input->post('local_fisico');
        
        $local_fisico = new stdClass();
        $local_fisico->id = $local_fisico_array["id"];
        $local_fisico->nombre = $local_fisico_array["nombre"];
        
        $localfisicoactual = array("localFisicoActual" => $local_fisico);
        
        $this->session->set_userdata('local_fisico', $local_fisico);
        
        echo json_encode($localfisicoactual);
    }

    function cambiarempresa() {
        $empresa_id = $this->uri->segment(3);
        $this->session->set_userdata('empresa_id', $empresa_id);
    }

    function cambiarlocalempresa() {
        $local_empresa_id = $this->uri->segment(3);
        $this->session->set_userdata('local_empresa_id', $local_empresa_id);
    }

    function cerrarsesion() {

        $this->session->sess_destroy();
        redirect('login');
    }

}
