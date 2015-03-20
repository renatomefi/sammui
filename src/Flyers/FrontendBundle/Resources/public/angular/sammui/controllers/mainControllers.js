'use strict';

/* Controllers */
angular.module('sammui.mainControllers', ['ngCookies'])

    .controller('UiCtrl', ['$rootScope', '$scope', function ($rootScope, $scope) {
        // User agent displayed in home page
        $scope.userAgent = navigator.userAgent;

    }])
    .controller('MyCtrl1', ['$rootScope', '$scope', function ($rootScope, $scope) {
        // If not authenticated, go to login

        //if (typeof $rootScope.userAuth === "undefined") {
        //    $window.location = '#/login';
        //    return;
        //}

        // Simple communication sample, return world
        $scope.hello = 'Hello';
    }])
    .controller('MyCtrl2', ['$rootScope', '$scope', function ($rootScope, $scope) {

    }]);