<?php

Class Menu extends CI_Controller {

    private $nombre;
    private $mostrar;
    private $iniciar_grupo;
    private $iniciar_elemento;
    private $menu;
    private $menus_disponibles;
    private $permisos;

    public function __construct() {
        parent::__construct();

        $this->load->helper('url_helper');
        
        
    }

    public function accesoslocalfisicousuario() {
        $menuJson = $this->getMenuLocalFisicoJson();

        echo json_encode($menuJson);
    }

    public function accesoslocalempresausuario() {
        
    }

    public function getMenuLocalFisicoJson() {
        $usuario_id = $this->session->userdata('usuario_id');
        $cambiarclave = $this->session->userdata('cambiarclave');

        $accesos = $this->session->userdata('accesos');
        $acceso_locales_fisicos = $accesos["acceso_locales_fisicos"];
        $local_fisico = $this->session->userdata('local_fisico');
        $local_fisico_id = $local_fisico->id;
        $local_fisico_nombre = $local_fisico->nombre;

        $menu = array();

        $keylocales = array_map(function($item) {
            return $item->local_id;
        }, $acceso_locales_fisicos);

        $keylocal = array_search($local_fisico_id, $keylocales);

//        $keylocal = array_search($local_fisico_id, array_column($acceso_locales_fisicos, "local_id"));


        if ($keylocal === false) {
            
        } else {

            $datoslocal = $acceso_locales_fisicos[$keylocal];


            $this->permisos = $datoslocal->permisos;
            $this->iniciar_grupo = '';
            $this->iniciar_elemento = '';
            $this->mostrar = false;
            $this->menus_disponibles = array(
                'menuconfiguracion' => 'menuconfiguracion'
            );


            if (!isset($usuario_id)) {
                $this->session->sess_destroy();
                redirect('login');
            } elseif ($cambiarclave == '1') {
                $this->session->sess_destroy();
                redirect('login');
            } else {
                foreach ($this->menus_disponibles as $menuDisponibleId => $menuDisponible) {
                    $this->nombre = '';
                    $this->menu = array();
                    $this->menu = $this->$menuDisponible();

                    $menuLateral = array();
                    foreach ($this->menu as $nombreMenu => $datosMenu) {
                        $subMenus = array('nombre' => $nombreMenu, 'datos' => array());
                        foreach ($datosMenu['elementos'] as $nombreSubMenu => $datosSubMenu) {

                            if (isset($this->permisos[$datosSubMenu['modulo']]->ver) and $this->permisos[$datosSubMenu['modulo']]->ver > 0) {
                                if ($datosSubMenu['destino'] == '') {
                                    $subMenus['datos'][] = array(
                                        'nombre' => $nombreSubMenu,
                                        'modulo' => $datosSubMenu['modulo'],
                                        'accion' => $datosSubMenu['accion'],
                                        'url' => '/' . $datosSubMenu['modulo'],
                                        'destino' => $datosSubMenu['destino'],
                                        'funcion' => $datosSubMenu['funcion']
                                    );
                                } else {
                                    $subMenus['datos'][] = array(
                                        'nombre' => $nombreSubMenu,
                                        'url' => $datosSubMenu['modulo'] . '/' . $datosSubMenu['accion'],
                                        'funcion' => $datosSubMenu['funcion'],
                                        'destino' => $datosSubMenu['destino']
                                    );
                                }
                            }
                        }

                        if (count($subMenus['datos']) > 0) {
                            $menuLateral[] = $subMenus;
                        }

                        if (count($menuLateral) > 0) {
                            if ($this->nombre == 'Servicio') {
                                $menu[] = array(
                                    'nombre' => $this->nombre,
                                    'url' => 'index',
                                    'datos' => $menuLateral,
                                    'activo' => false
                                );
                            } else {
                                $menu[] = array(
                                    'nombre' => $this->nombre,
                                    'datos' => $menuLateral,
                                    'activo' => false
                                );
                            }
                        }
                    }
                }
            }
        }

        return $menu;
    }

    public function getMenuLocalEmpresaJson() {
        
    }

    private function menuconfiguracion() {
        $this->nombre = 'Configuración';
        return array(
            'Configuración' => array(
                'mostrar' => false,
                'elementos' => array(
                    'Módulos' => array('modulo' => 'modulo', 'accion' => 'index', 'destino' => '#main', 'funcion' => 'cargarModulos'),
                )
            )
        );
    }

}
