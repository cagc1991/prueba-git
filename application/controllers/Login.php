<?php

Class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url_helper');

        if ($this->session->userdata('usuario')) {
            redirect('menuconfiguracion');
        }
    }

    function index() {
        $this->load->view('login/login');
    }

    function verificar() {

        $this->load->model('usuario');

        $revisarusuario = $this->usuario->validarusuario();
        $revisarusuarioestado = $this->usuario->validarusuarioestado();
        $revisarcontraseña = $this->usuario->validarcontraseña();

        if ($revisarusuario) {
            if ($revisarusuarioestado) {
                if ($revisarcontraseña) {

                    $conslocalesfisicos = $this->db->query("select lf.id as local_id, lf.nombre as local, r.id as rol_id, r.nombre as rol"
                            . " from acceso_local_fisico alf"
                            . " inner join local_fisico lf on lf.id = alf.local_fisico_id"
                            . " inner join rol r on r.id = alf.rol_id"
                            . " where alf.usuario_id = " . $revisarcontraseña->id . ";");
                    $sellocalesfisicos = $conslocalesfisicos->result();
                    $cantlocalesfisicos = count($sellocalesfisicos);


                    if ($cantlocalesfisicos == 0) {
                        $this->session->set_flashdata('msg', "Debe asignar al usuario a al menos un local físico.");
                        redirect('login', 'refresh');
                    }


                    $accesos = array();

                    $accesos["acceso_locales_fisicos"] = array();
                    $accesos["acceso_empresas"] = array();
                    $accesos["acceso_locales_empresa"] = array();


                    $temlocalfisicoid = 0;
                    $temlocalfisiconombre = "";


                    foreach ($sellocalesfisicos as $fila) {

                        if ($temlocalfisicoid == 0) {
                            $temlocalfisicoid = $fila->local_id;
                            $temlocalfisiconombre = $fila->local;
                        }

                        $fila1 = $fila;

                        $tem_rol_id = $fila->rol_id;

                        $conspermisostem = $this->db->query("select p.*, r.nombre as rol, m.nombre as modulo from permiso p"
                                . " inner join rol r on r.id = p.rol_id"
                                . " inner join modulo m on m.id = p.modulo_id"
                                . " where p.rol_id = $tem_rol_id;");

                        $permisostemini = $conspermisostem->result();

                        $fila1->permisos = array();

                        foreach ($permisostemini as $fila2) {
                            $modulotem = $fila2->modulo;

                            $fila1->permisos[$modulotem] = $fila2;
                        }


                        $accesos["acceso_locales_fisicos"][] = $fila1;
                    }


                    $conslocalesempresa = $this->db->query("select le.id as local_id, le.nombre as local, r.id as rol_id, r.nombre as rol,"
                            . " e.id as empresa_id, e.razon_social, e.abreviatura, e.ruc"
                            . " from acceso_local_empresa ale"
                            . " inner join local_empresa le on le.id = ale.local_empresa_id"
                            . " inner join empresa e on e.id = le.empresa_id"
                            . " inner join rol r on r.id = ale.rol_id"
                            . " where ale.usuario_id = " . $revisarcontraseña->id . ";");
                    $sellocalesempresa = $conslocalesempresa->result();
                    $cantlocalesempresa = count($sellocalesempresa);

                    $temempresa = "";
                    $temempresaid = 0;
                    $temempresanombre = "";
                    $temlocalempresaid = 0;
                    $temlocalempresanombre = "";
                    $cantempresas = 0;

                    foreach ($sellocalesempresa as $fila) {

                        if ($temempresaid == 0) {
                            $temempresaid = $fila->empresa_id;
                            $temempresanombre = $fila->abreviatura;
                        }

                        if ($temlocalempresaid == 0) {
                            $temlocalempresaid = $fila->local_id;
                            $temlocalempresanombre = $fila->local;
                        }

                        $empresa = $fila->abreviatura;
                        $empresa_id = $fila->empresa_id;

                        $fila1 = $fila;

                        if ($empresa <> $temempresa) {
                            $temempresa = $empresa;


                            $accesos["acceso_empresas"][] = array(
                                "empresa" => $empresa,
                                "empresa_id" => $empresa_id
                            );

                            $cantempresas++;
                        }

                        $tem_rol_id = $fila->rol_id;


                        $conspermisostem = $this->db->query("select p.*, r.nombre as rol, m.nombre as modulo from permiso p"
                                . " inner join rol r on r.id = p.rol_id"
                                . " inner join modulo m on m.id = p.modulo_id"
                                . " where p.rol_id = $tem_rol_id;");

                        $permisostemini = $conspermisostem->result();

                        $fila1->permisos = array();

                        foreach ($permisostemini as $fila2) {
                            $modulotem = $fila2->modulo;

                            $fila1->permisos[$modulotem] = $fila2;
                        }


                        $accesos["acceso_locales_empresa"][] = $fila1;
                    }

                    $rol_id = $sellocalesfisicos[0]->rol_id;

                    $conspermisos = $this->db->query("select p.*, r.nombre as rol, m.nombre as modulo from permiso p"
                            . " inner join rol r on r.id = p.rol_id"
                            . " inner join modulo m on m.id = p.modulo_id"
                            . " where p.rol_id = $rol_id;");

                    $permisosInicial = $conspermisos->result();

                    $permisosactuales = array();

                    foreach ($permisosInicial as $fila) {
                        $moduloactual = $fila->modulo;

                        $permisosactuales[$moduloactual] = $fila;
                    }


                    $this->session->set_userdata('permisosactuales', $permisosactuales);

                    $menusdisponibles = array();

                    if (isset($permisosactuales["menuconfiguracion"])) {
                        $permisover = $permisosactuales["menuconfiguracion"]->ver;
                        if ($permisover >= 1) {
                            $menusdisponibles[] = (array) $permisosactuales["menuconfiguracion"];
                        } else {
                            
                        }
                    } else {
                        
                    }

                    $keyinicios = array_map(function($item) {
                        return $item["modulo"];
                    }, $menusdisponibles);

                    $keyinicio = array_search("menuconfiguracion", $keyinicios);

                    if ($keyinicio === false) {
                        $this->session->set_flashdata('msg', "Debe tener Acceso al menos al Menú Configuración");
                        redirect('login', 'refresh');
                    } else {

                        $temlocalfisico = new stdClass();
                        $temlocalfisico->id = $temlocalfisicoid;
                        $temlocalfisico->nombre = $temlocalfisiconombre;
                        $temempresa = new stdClass();
                        $temempresa->id = $temempresaid;
                        $temempresa->nombre = $temempresanombre;
                        $temlocalempresa = new stdClass();
                        $temlocalempresa->id = $temlocalempresaid;
                        $temlocalempresa->nombre = $temlocalempresanombre;


                        $this->session->set_userdata('usuario_id', $revisarcontraseña->id);
                        $this->session->set_userdata('usuario', $revisarcontraseña->usuario);
                        $this->session->set_userdata('cambiarclave', $revisarcontraseña->cambiarclave);

                        $this->session->set_userdata('local_fisico', $temlocalfisico);
                        $this->session->set_userdata('empresa', $temempresa);
                        $this->session->set_userdata('local_empresa', $temlocalempresa);

                        $this->session->set_userdata('accesos', $accesos);

//                        $this->session->set_userdata('menus', $menusdisponibles);

                        redirect('menuconfiguracion');
                    }
                } else {
                    $this->session->set_flashdata('msg', "Contraseña Inválida");
                    redirect('login', 'refresh');
                }
            } else {
                $this->session->set_flashdata('msg', "Usuario Deshabilitado");
                redirect('login', 'refresh');
            }
        } else {
            $this->session->set_flashdata('msg', "Usuario No Existe");
            redirect('login', 'refresh');
        }
    }

}
