<?php

Class Modulo extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->helper('url_helper');
    }

    public function index() {     
        $vista = $this->load->view('modulo/inicio', '', true);
        
        echo $vista;
    }

}
