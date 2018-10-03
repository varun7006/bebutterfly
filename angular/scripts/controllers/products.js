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
            .controller('productCtrl', productCtrl);

    productCtrl.$inject = ['$scope', '$http', '$rootScope', '$localStorage', 'sessionService', '$state', 'toaster', 'ProductService', 'CoreService','ShopService'];
    function productCtrl($scope, $http, $rootScope, $localStorage, sessionService, $state, toaster, ProductService,CoreService,ShopService) {
        $scope.product = {};
        $scope.shopList = [];
        $scope.productList = [];
        $scope.productOwnerList = [];
        $scope.brandList = [];
        $scope.categoryList = [];
        $scope.IsShopKeeper = false;
        $scope.errorMsg = null;
        // reset login status
//        AuthenticationService.ClearCredentials();
        $scope.saveProductData = function () {
            
            ProductService.saveProductData($scope.product).success(function (response) {
                if (response.status == 'SUCCESS') {
                    toaster.pop('success', "Success", response.msg);
                } else {
                    toaster.pop('error', "Error", response.msg);
                }
            }).error(function (response) {
                toaster.pop('error', "Error", "There is some error. Contact Admin.");
            });
        };

        $scope.getProductList = function () {
            
            $rootScope.spinner.on();
            ProductService.getProductList().success(function (response) {
                $rootScope.spinner.off();
                if (response.status == 'SUCCESS') {
                    $scope.productList = response.value;
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
            ProductService.getShopOwnerList().success(function (response) {
                if (response.status == 'SUCCESS') {
                    $scope.productOwnerList = response.value;
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
        
      
        
        $scope.getBrandData = function () {
            CoreService.getBrandData().success(function (response) {
                if (response.status == 'SUCCESS') {
                    $scope.brandList = response.value;
                } else {
                    toaster.pop('error', "Error", response.msg);
                }
            }).error(function (response) {
                toaster.pop('error', "Error", "There is some error. Contact Admin.");
            });
        };
        
        $scope.getCategoryData = function () {
            CoreService.getCategoryData().success(function (response) {
                if (response.status == 'SUCCESS') {
                    $scope.categoryList = response.value;
                } else {
                    toaster.pop('error', "Error", response.msg);
                }
            }).error(function (response) {
                toaster.pop('error', "Error", "There is some error. Contact Admin.");
            });
        };
        if ($state.current.data.getProduct == 'TRUE') {
            $scope.getProductList();
        }
        else{
            $scope.getShopList();
             $scope.getCategoryData();
             $scope.getBrandData();
        }

    }

})();
