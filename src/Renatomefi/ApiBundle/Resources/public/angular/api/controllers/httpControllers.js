'use strict';

angular.module('sammui.apiHttpControllers', ['ngRoute'])
        .controller('httpLogs', ['$rootScope', '$scope', 'loadingHttpList',
            function ($rootScope, $scope, loadingHttpList) {

                $rootScope.loadingList.history.map(function (config) {
                    if (config.success) {
                        $scope.historySuccess++;
                    } else {
                        $scope.historyError++;
                    }
                });
            }
        ])
        .filter('counter', function () {
            return function (history, success) {
                var validator = (angular.isUndefined(success)) ? true : false;
                var counter = 0;
                history.map(function (item) {
                    if (item.config.success === validator) {
                        counter++;
                    }
                });

                return counter;
            }
        })
        .filter('highlight', function ($sce) {
            return function (text, phrase) {
                if (phrase) {
                    text = text.replace(
                            new RegExp('(' + phrase + ')', 'gi'),
                            '<span class="highlighted">$1</span>'
                            )
                }
                return $sce.trustAsHtml(text)
            }
        });