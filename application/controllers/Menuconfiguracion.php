<?php

Class Menuconfiguracion extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->helper('url_helper');
        
        if (!$this->session->userdata('usuario')) {
            redirect('login');
        }
    }
    
    function index() {
        $this->load->view('menuconfiguracion/inicio');
    }
}