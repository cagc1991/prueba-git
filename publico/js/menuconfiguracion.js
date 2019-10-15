generalApp.controller('menuconfiguracionCtrl', function ($scope, $http, $rootScope, alertas) {
    $scope.panelUsuario = 0;

    $scope.cambiarPanelUsuario = function (idPanel) {
        $scope.panelUsuario = idPanel;
    };

    $scope.modulos = {};


    $scope.cargarModulos = function (im)
    {
        alert("hola Modulos" + im);
    };


});
