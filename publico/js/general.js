var generalApp = angular.module('generalApp', ['ngRoute']);

generalApp.factory('alertas', function () {
    return {
        Alert: function (msg) {
            var title = arguments[1] || 'Alerta';
            $('#AlertaLabel').html(title);
            $('#AlertaMsg').html(msg);
            $("#Alerta").modal('show');
        },
        Confirm: function (msg, callback) {
            var title = arguments[2] || 'Confirmar';
            $('#ConfirmLabel').html(title);
            $('#ConfirmMsg').html(msg);
            $("#Confirm").modal('show');
            $("#ConfirmAceptar").unbind('click');
            $("#ConfirmAceptar").click(function () {
                callback();
            });
        }
    };
});



generalApp.controller('generalCtrl', function ($scope, $http, $location, $rootScope, $compile, alertas, $timeout, $controller) {
    
    $controller('menuconfiguracionCtrl', {$scope: $scope});
    $controller('menupersonalCtrl', {$scope: $scope});
    
    $scope.moduloaccion = '';
    $scope.modulo = '';
    $scope.accion = '';
    $scope.alertas = {};
    $scope.menu = {};
    $scope.menuActivo = "";
    $scope.empresas = {};
    $scope.empresaActual = {};
    $scope.localesEmpresas = {};
    $scope.localEmpresaActual = {};
    $scope.localesFisicos = {};
    $scope.localFisicoActual = {};
    $scope.usuario = "";
    $scope.cargandoDatos = "";

    $scope.manejarError = function (status) {
        if (status === '401') {
            $scope.cerrarSesion();
        }
        else if (status === '403') {
            alertas.Alert('No tiene acceso a esta opción.', 'Acceso denegado');
        } else {
            alertas.Alert('Error ' + status, 'Error');
        }
    };

    $scope.setFormulario = function (modulo, accion) {
        console.log('SetFormulario: ' + modulo + ' ' + accion);
        $scope.modulo = modulo;
        $scope.accion = accion;
        $scope.moduloaccion = modulo + '/' + accion;
    };

    $scope.cargarMenuLocalFisico = function () {
        $http.get('menu/accesoslocalfisicousuario').then(function (response) {
            $scope.menu = response.data;
            $scope.menuActivo = 0;

            if (paginaActual != '') {
                for (menuActual in response.data) {
                    if (response.data[menuActual].nombre == paginaActual) {
                        $scope.menuActivo = menuActual;
                    }
                }
            }

            if ($scope.menu[$scope.menuActivo].datos[0].datos[0].destino == '') {
                $location.path($scope.menu[$scope.menuActivo].datos[0].datos[0].url.substring(0));
            } else {
                // $rootScope.$emit("CallParentMethod", {});
                eval('$scope.' + $scope.menu[$scope.menuActivo].datos[0].datos[0].funcion + '(1);');
                $scope.cargarModulo($scope.menu[$scope.menuActivo].datos[0].datos[0].url, $scope.menu[$scope.menuActivo].datos[0].datos[0].destino);
            }
        })
    };


    $scope.cargarLocalesFisicos = function () {
        $http.get('sesion/cargarlocalesfisicos').then(function (response) {
            $scope.localesFisicos = response.data.localesFisicos;
            $scope.localFisicoActual = response.data.localFisicoActual;
            $scope.usuario = response.data.usuario;
        });
    };

    $scope.cambiarMenu = function (idMenu) {
        $scope.menuActivo = idMenu;
        $location.path($scope.menu[$scope.menuActivo].datos[0].datos[0].url.substring(0));
    }

    $scope.cambiarLocalFisico = function (localfisico) {
//        $http.post('sesion/cambiarlocalfisico',
//                {
//                    datatype: JSON,
//                    data: {
//                        "local_fisico": localfisico
//                    }
//                }
//        ).then(function (response) {
//            $scope.cargarMenuLocalFisico();
//        });
        $.ajax({type: 'POST', url: 'sesion/cambiarlocalfisico', datatype: JSON, data: {"local_fisico": localfisico}}).then(function (response) {
            $scope.cargarLocalesFisicos();
            $scope.cargarMenuLocalFisico();
        });
    };

    $rootScope.$on("$viewContentLoaded", function () {
        $('#main').html('');
    });

    $scope.cargarModulo = function (cargarurl, cargarhtml) {
        var inicio = new Date;
        $location.path("/");
        inicio = inicio.getTime();
        if (bloquear) {
            return 0;
        }
        var cargardata = arguments[2] || '';
        $("#dump").html("");

        $.ajax({type: 'POST', url: cargarurl, data: JSON.stringify({"local_fisico": $scope.localFisicoActual.id}) + cargardata}).then(function (response, Doc) {
            $scope.urlCargada = cargarurl;
            Doc = response;

            html = angular.element(Doc);
            $(cargarhtml).html(Doc);
            $compile(html)($scope);
        }, function (response) {
            $scope.manejarError(response.status);
        });
    };


});

generalApp.controller('menulocalfisicoCtrl', function ($scope, $interval, $http, alertas) {
    $scope.paginaActual = 1;
    $scope.cargarLocalesFisicos();
    $scope.cargarMenuLocalFisico();

});

generalApp.controller('moduloCtrl', function ($scope, $http, $route, alertas) {
    $scope.columnas = {};
    $scope.permisos = {};
    $scope.registros = {};
    $scope.registro = {};
    $scope.buscarRegistro = {};
    $scope.idSeleccionado = 0;
    $scope.totalRegistros = 0;
    $scope.totalPaginas = 0;
    $scope.paginaActual = 1;
    $scope.limiteRegistros = 20;
    $scope.campoOrden = '';
    $scope.asc = true;

    $scope.resetVariables = function () {
        $scope.columnas = {};
        $scope.permisos = {};
        $scope.registros = {};
        $scope.registro = {};
        $scope.buscarRegistro = {};
        $scope.buscarRegistro.formulario = 1;
        $scope.idSeleccionado = 0;
        $scope.totalRegistros = 0;
        $scope.totalPaginas = 0;
        $scope.paginaActual = 1;
        $scope.limiteRegistros = 20;
        $scope.campoOrden = '';
        $scope.asc = true;
    };

//    $scope.cambiarPagina = function (pagina, url = '') {
//        $scope.paginaActual = pagina;
//        $scope.cargar(url);
//    };
//
//    $scope.cargar = function (url = '') {
//        if (url === '')
//            url = $route.current.templateUrl + '/index';
//        $scope.idSeleccionado = 0;
//        var datos = {
//            'limiteRegistros': $scope.limiteRegistros,
//            'paginaActual': $scope.paginaActual,
//            'campoOrden': $scope.campoOrden,
//            'asc': $scope.asc,
//            'buscar': $scope.buscarRegistro
//        };
//        $scope.cargandoDatos = 'Cargando...';
//        $http({method: 'POST', url: url, data: JSON.stringify({"local": $scope.localActual.id, "datos": datos})}).then(function (response) {
//            $scope.registros = response.data.registros;
//            $scope.permisos = response.data.permisos;
//            $scope.limiteRegistros = response.data.limiteRegistros;
//            $scope.totalRegistros = response.data.totalRegistros;
//            $scope.paginaActual = response.data.paginaActual;
//            $scope.totalPaginas = response.data.totalPaginas;
//            $scope.registroInicial = ((response.data.paginaActual - 1) * response.data.limiteRegistros) + 1;
//            $scope.registroFinal = (response.data.paginaActual * response.data.limiteRegistros);
//            $scope.campoOrden = response.data.campoOrden;
//            $scope.asc = response.data.asc;
//            $scope.columnas = response.data.columnas;
//            $scope.cargandoDatos = '';
//        }, function (response) {
//            $scope.manejarError(response.status);
//            $scope.cargandoDatos = '';
//        });
//    };




});