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
            .controller('userCtrl', userCtrl);

    userCtrl.$inject = ['$scope', '$http', '$rootScope', '$localStorage', 'sessionService', '$state','toaster', 'UserService'];
    function userCtrl($scope, $http, $rootScope, $localStorage, sessionService, $state,toaster, UserService) {
        $scope.user = {};
        $scope.userList = [];
        $scope.IsShopKeeper = false;
        $scope.errorMsg = null;
        // reset login status
//        AuthenticationService.ClearCredentials();
        $scope.saveUserBasicData = function () {
            $scope.dataLoading = true;
            UserService.saveUserData($scope.user.basic_data).success(function (response) {
                if(response.status=='SUCCESS'){
                    toaster.pop('success', "Success", response.msg);
                }else{
                    toaster.pop('error', "Error", response.msg);
                }
            }).error(function (response) {
                toaster.pop('error', "Error", "There is some error. Contact Admin.");
            });
        };
        
        $scope.getUserList = function () {
            $rootScope.spinner.on();
            UserService.getUserList().success(function (response) {
                $rootScope.spinner.off();
                if(response.status=='SUCCESS'){
                    $scope.userList = response.value;
                    toaster.pop('success', "Success", response.msg);
                }else{
                    toaster.pop('error', "Error", response.msg);
                }
            }).error(function (response) {
//                $rootScope.spinner.off();
                toaster.pop('error', "Error", "There is some error. Contact Admin.");
            }).finally(function(){
                $rootScope.spinner.off();
            });
            
        }

        $scope.getUserList();
    }

})();
