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
            .controller('shopCtrl', shopCtrl);

    shopCtrl.$inject = ['$scope', '$http', '$rootScope', '$localStorage', 'sessionService', '$state', 'toaster', 'ShopService', 'CoreService'];
    function shopCtrl($scope, $http, $rootScope, $localStorage, sessionService, $state, toaster, ShopService,CoreService) {
        $scope.shop = {};
        $scope.shopList = [];
        $scope.countryList = [];
        $scope.shopOwnerList = [];
        $scope.stateList = [];
        $scope.cityList = [];
        $scope.IsShopKeeper = false;
        $scope.errorMsg = null;
        // reset login status
//        AuthenticationService.ClearCredentials();
        $scope.saveShopData = function () {
            
            ShopService.saveShopData($scope.shop).success(function (response) {
                if (response.status == 'SUCCESS') {
                    toaster.pop('success', "Success", response.msg);
                } else {
                    toaster.pop('error', "Error", response.msg);
                }
            }).error(function (response) {
                toaster.pop('error', "Error", "There is some error. Contact Admin.");
            });
        };

        $scope.getShopList = function () {
            
            $rootScope.spinner.on();
            ShopService.getShopList().success(function (response) {
                $rootScope.spinner.off();
                if (response.status == 'SUCCESS') {
                    $scope.shopList = response.value;
                } else {
                    toaster.pop('error', "Error", response.msg);
                }
            }).error(function (response) {
//                $rootScope.spinner.off();
                toaster.pop('error', "Error", "There is some error. Contact Admin.");
            }).finally(function () {
                $rootScope.spinner.off();
            });

        }
       
        $scope.getShopOwnerList = function () {
            ShopService.getShopOwnerList().success(function (response) {
                if (response.status == 'SUCCESS') {
                    $scope.shopOwnerList = response.value;
                } else {
                    toaster.pop('error', "Error", response.msg);
                }
            }).error(function (response) {
                toaster.pop('error', "Error", "There is some error. Contact Admin.");
            });
        };
        
        $scope.getCountryList = function () {
            
            CoreService.getCountryData().success(function (response) {
                if (response.status == 'SUCCESS') {
                    $scope.countryList = response.value;
                } else {
                    toaster.pop('error', "Error", response.msg);
                }
            }).error(function (response) {
                toaster.pop('error', "Error", "There is some error. Contact Admin.");
            });
        };
        
        $scope.getStateList = function () {
            CoreService.getStateData($scope.shop.country_id).success(function (response) {
                if (response.status == 'SUCCESS') {
                    $scope.stateList = response.value;
                } else {
                    toaster.pop('error', "Error", response.msg);
                }
            }).error(function (response) {
                toaster.pop('error', "Error", "There is some error. Contact Admin.");
            });
        };
        
        $scope.getCityList = function () {
            CoreService.getCityData($scope.shop.state_id).success(function (response) {
                if (response.status == 'SUCCESS') {
                    $scope.cityList = response.value;
                } else {
                    toaster.pop('error', "Error", response.msg);
                }
            }).error(function (response) {
                toaster.pop('error', "Error", "There is some error. Contact Admin.");
            });
        };
        
        if ($state.current.data.getShop == 'TRUE') {
            $scope.getShopList();
        }else{
             $scope.getCountryList();
             $scope.getShopOwnerList();
        }

    }

})();
