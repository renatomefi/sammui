'use strict';

angular.module('sammui.apiAuthControllers', ['ngRoute'])

    .controller('oAuthLogin', ['$scope', 'oAuth',
        function ($scope, oAuth) {
            $scope.login = function () {
                var loginResult = oAuth.beAuthenticated({username: $scope.username, password: $scope.password});

                loginResult.then(function (success) {
                    $scope.loginSuccess = success;
                }, function (error) {
                    $scope.loginError = error;
                });

                loginResult.finally(function () {
                    $scope.oauthLoginForm.$submitted = false;
                });
            };

            $scope.logout = function () {
                oAuth.logout();
                $scope.loginSuccess = null;
                $scope.loginError = null;
            };
        }
    ]);
