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

    loginCtrl.$inject = ['$scope', '$http', '$rootScope', '$localStorage', 'sessionService',  '$state'];
    function loginCtrl($scope, $http, $rootScope, $localStorage, sessionService,  $state) {
        $scope.errorMsg = null;
        $scope.loginFunction = function () {

            if ($scope.username == '') {
                $scope.errorMsg = 'Please Provide Username';
                $('#loginError').modal('show');
                return false;
            } else if ($scope.password == '') {
                $scope.errorMsg = 'Please Provide Password';
                $('#loginError').modal('show');
                return false;
            } else {
                $scope.loginData = {uname: $scope.username, pwd: $scope.password, myuid: localStorage.myuid};
                toaster.pop('wait', "Please wait..", 'Checking your login credentials..');
                $rootScope.spinner.on();
                $http({
                    method: 'POST',
                    url: baseUrlApi + 'index.php/login/loginuser',
                    crossDomain: true,
                    xhrFields: {withCredentials: true},
                    data: 'data=' + encodeURIComponent(angular.toJson($scope.loginData)),
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).success(function (jsondata) {
                    $rootScope.spinner.off();
                    if (jsondata.status == 'SUCCESS') {
                        sessionService.setSessionData(jsondata.session);
                        menuService.setMenuData(jsondata.menuJson);
                        localStorage.stateId = jsondata.session.stateid;
                        localStorage.stateName = jsondata.session.statename;
                        if ($scope.username == 'tata') {
                            $state.go('app.page.realtimedashboard');
                        } else {
                            $state.go('app.dashboard');
                        }
                    } else if (jsondata.status == 'PWD') {
                        $state.go('access.changepassword');
                    } else {
                        toaster.clear();
                        sessionService.setSessionData(null);
                        toaster.pop('error', "Error", jsondata.message);
                    }
                }).error(function (error) {
                    toaster.pop('error', "Error", 'Logout session.Please Login again..');
                });
            }
        }

        $scope.userSessionData = JSON.parse(sessionService.getSessionData());
        $scope.changePassword = function () {
            if ($scope.pass.new != $scope.pass.renew) {
                toaster.pop('error', "Error", "New Password and Re enter new password does not match.");
                return false;
            }
            if (confirm("Are you sure,you want to change password?")) {
                $rootScope.spinner.on();
                $http({
                    method: 'POST',
                    url: baseUrlApi + 'index.php/changepassword',
                    data: 'data=' + encodeURIComponent(angular.toJson($scope.pass)),
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).success(function (jsondata) {
                    $rootScope.spinner.off();
                    if (jsondata.status == 'SUCCESS') {
                        alert(jsondata.message);
                        $state.go('access.signin');
                    } else {
                        toaster.pop('error', "Error", jsondata.message);
                    }
                }).error(function (error) {
                    toaster.pop('error', "Error", 'Somethig went wrong.Please contact to admin.');
                });
            }
        }

    }

})();
