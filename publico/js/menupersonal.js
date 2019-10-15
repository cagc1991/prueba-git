generalApp.controller('menupersonalCtrl', function ($scope, $http, $rootScope, alertas) {   
    $scope.panelUsuario = 0;

    $scope.cambiarPanelUsuario = function (idPanel) {
        $scope.panelUsuario = idPanel;
    };

    $scope.personas = {};


    $scope.cargarPersonas = function (ip)
    {
        alert("hola Personas");
    };
    
});