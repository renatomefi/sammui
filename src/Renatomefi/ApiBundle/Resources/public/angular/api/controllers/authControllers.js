'use strict';

angular.module('sammui.apiAuthControllers', ['ngRoute'])

    .controller('oAuthLogin', ['$scope', 'oAuth',
        function ($scope, oAuth) {
            $scope.login = function () {
                // to-do: Treat errors
                oAuth.beAuthenticated({username: $scope.username, password: $scope.password});
            };
        }
    ]);
