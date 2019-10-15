<!DOCTYPE html>

<html>
    <head>
        <title>Sistema</title>
        <link rel = "stylesheet" href = "<?php echo base_url(); ?>publico/css/jquery-ui.css">
        <link rel = "stylesheet" href = "<?php echo base_url(); ?>publico/bootstrap-table-master/src/bootstrap.css">
        <link rel = "stylesheet" href = "<?php echo base_url(); ?>publico/bootstrap-table-master/src/bootstrap-table.css">
        <link rel = "stylesheet" href = "<?php echo base_url(); ?>publico/css/bootstrap-theme.css">
        <link rel = "stylesheet" href = "<?php echo base_url(); ?>publico/css/general.css"> 
        <script type = "text/javascript" src = "<?php echo base_url(); ?>publico/js/jquery-2.1.4.js"></script>
        <script type = "text/javascript" src = "<?php echo base_url(); ?>publico/js/jquery-ui.js"></script>
        <script type = "text/javascript" src = "<?php echo base_url(); ?>publico/js/angular.js"></script>
        <script type = "text/javascript" src = "<?php echo base_url(); ?>publico/js/angular-route.js"></script>
        <script type = "text/javascript" src = "<?php echo base_url(); ?>publico/js/funcionesgenerales.js"></script>
        <script type = "text/javascript" src = "<?php echo base_url(); ?>publico/js/general.js"></script>
        <script type = "text/javascript" src = "<?php echo base_url(); ?>publico/js/menuconfiguracion.js"></script>
        <script type = "text/javascript" src = "<?php echo base_url(); ?>publico/js/menupersonal.js"></script>
        <script type = "text/javascript" src = "<?php echo base_url(); ?>publico/js/bootstrap.js"></script>
        <!--<script type = "text/javascript" src = "<?php // echo base_url();   ?>publico/js/bootstrap-table.js"></script>-->      

        <script type="text/javascript">
//            import pdfMake from "pdfmake/build/pdfmake";
//            import pdfFonts from "pdfmake/build/vfs_fonts";
            var $j = jQuery;
            var urlBase = "<?php echo base_url(); ?>";
            var paginaActual = 'Configuración';
//            var tagsUbigeoDatos = [<?php // echo $this->tagsUbigeoDatos;    ?>];
        </script>
    </head>
    <body ng-app = "generalApp" ng-controller="generalCtrl">
        <div ng-controller = "menuconfiguracionCtrl">
<!--        style = 'background-image: url("<?php // echo base_url();   ?>publico/imagenes/logo.jpg"); background-repeat: no-repeat; background-attachment: fixed; background-position-x: 300px; background-position-y: 150px;'*/-->        
            <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation" ng-controller="menulocalfisicoCtrl">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="" onclick="$('.sidebar').toggle()">&nbsp;Botón</a>
                    </div>            
                    <div>
                        <ul class="nav navbar-nav" ng-repeat="(idMenu, itemMenu) in menu">
                            <li ng-class="{
                                    active: idMenu == menuActivo
                                }"><a href="{{itemMenu.url}}">{{itemMenu.nombre}}</a></li>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">                        
                            <li class="dropdown">
                                <a href="#" target="_self" id="usuario" role="button" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span><span class="nav-texto"> {{usuario}} </span><b class="caret"></b></a>
                                <ul class="dropdown-menu" role="menu" aria-labelledby="sucursales">
                                    <li role="presentation">
                                    <li><a role="menuitem" tabindex="-1" href = "sesion/cerrarsesion">Cerrar Sesión</a></li>
                                </ul>
                            </li>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                            <li class="dropdown">
                                <a href="#" target="_self" id="localesAlmacen" role="button" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-home"></span><span class="nav-texto"> {{localFisicoActual.nombre}} </span><b class="caret"></b></a>
                                <ul class="dropdown-menu" role="menu" aria-labelledby="sucursales">
                                    <li role="presentation" ng-repeat="localFisico in localesFisicos">
                                        <a role="menuitem" tabindex="-1" href="#" ng-click="cambiarLocalFisico(localFisico)">{{localFisico.nombre}}</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <div class="container-fluid">
                <div class="row col-md-12 contenedor">
                    <div id="sidebar" class="sidebar">
                        <div id="menu2" class="boxed">
                            <div class="content">
                                <div class="panel-group" id="menu-lateral">
                                    <div class="panel panel-primary" ng-repeat="(idMenu, datosMenu) in menu[menuActivo].datos">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#menu-lateral" href="#{{datosMenu.nombre}}" target="_self">
                                                    {{datosMenu.nombre}}
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="{{datosMenu.nombre}}" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <ul class="nav nav-pills nav-stacked">
                                                    <li ng-repeat="(idSubMenu, datosSubMenu) in datosMenu.datos">
                                                        <a ng-if="datosSubMenu.destino == ''" href="#!{{datosSubMenu.url}}" id="menu_modulo">{{datosSubMenu.nombre}}</a>
                                                        <a ng-if="datosSubMenu.destino != ''" ng-click="this[datosSubMenu.funcion](1); cargarModulo(datosSubMenu.url, datosSubMenu.destino);" href="#">{{datosSubMenu.nombre}}</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div id="principal" class="principal">
                        <div ng-controller="moduloCtrl" ng-view></div>
                    </div>
                    <div id="main" class="principal">
                    </div>
                    <!-- end #main -->
                    <div style="clear: both;">&nbsp;</div>
                </div>
            </div>

            <div id="footer" class="col-sm-5 col-sm-offset-2 col-md-5 col-md-offset-9">
                <div id="tiemporespuesta"></div>
                <p id="legal"></p>
                <p id="links"></p>
            </div>
            <div id="dump">
            </div>

            <div class="modal fade" id="Alerta" tabindex="-1" role="dialog" aria-labelledby="AlertaLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title" id="AlertaLabel"></h4>
                        </div>
                        <div class="modal-body" id="AlertaMsg">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="Confirm" tabindex="-1" role="dialog" aria-labelledby="ConfirmLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title" id="ConfirmLabel"></h4>
                        </div>
                        <div class="modal-body" id="ConfirmMsg">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" id="ConfirmAceptar" data-dismiss="modal">Aceptar</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="alerta" title="">
                <p/>
            </div>
            <div title="" id="cuadro_impresion">
                <p/>
            </div>
        </div>
    </body>
</html>