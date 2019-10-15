<?php

Class Persona extends CI_Controller {
    
    public function __construct() {
        parent::__construct();

        $this->load->helper('url_helper');
    }
    
    public function index() {
        $data = '<?xml
            
                ';
        $data .= "<input type = 'text' value = 'e'></input>";
        
        echo $data;
    }
    
}