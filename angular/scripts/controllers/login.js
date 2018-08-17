// code style: https://github.com/johnpapa/angular-styleguide 

(function () {
    'use strict';
    angular
            .module('app')
            .directive('ngEnter', function () {
                return function (scope, element, attrs) {
                    element.bind("keydown keypress", function (event) {
                        if (event.which === 13) {
                            scope.$apply(function () {
                                scope.$eval(attrs.ngEnter);
                            });

                            event.preventDefault();
                        }
                    });
                };
            })
            .controller('loginCtrl', loginCtrl);

    loginCtrl.$inject = ['$scope', '$http', '$rootScope', '$localStorage', 'sessionService',  '$state','toaster','AuthenticationService'];
    function loginCtrl($scope, $http, $rootScope, $localStorage, sessionService,  $state,toaster,AuthenticationService) {
        $scope.user = {};
        $scope.errorMsg = null;
        // reset login status
        AuthenticationService.ClearCredentials();
        $scope.login = function () {
            $scope.dataLoading = true;
            AuthenticationService.Login($scope.user.email, $scope.user.password, function(response) {
                if(response.status=='SUCCESS') {
                    AuthenticationService.SetCredentials($scope.username, $scope.password);
                    alert("121")
                    $state.go('app.dashboard');
                } else {
                    toaster.pop("error", "Error", response.msg);
                    $scope.dataLoading = false;
                }
            });
        };

        $scope.userSessionData = JSON.parse(sessionService.getSessionData());

    }

})();
