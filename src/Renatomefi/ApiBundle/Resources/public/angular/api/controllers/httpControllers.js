'use strict';

angular.module('sammui.apiHttpControllers', ['ngRoute'])
        .controller('httpLogs', ['$rootScope','$scope', 'loadingHttpList',
            function ($rootScope, $scope, loadingHttpList) {
                $scope.listLogs = $rootScope.loadingList.getHistory();
            } 
        ]);