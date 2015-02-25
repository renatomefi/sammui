'use strict';

angular.module('sammui.apiHttpServices', ['ngResource', 'ngRoute'])

    .service('loadingHttpList', function () {
        var list = [];
        var history = [];

        return {
            append: function (config, deferred) {
                list.push({
                    config: config,
                    deferred: deferred
                });
            },
            getItem: function (id) {
                return list[id];
            },
            clear: function () {
                history.concat(list);
                list = [];
            },
            getList: function () {
                return list;
            },
            getHistory: function () {
                return history;
            },
            list: list,
            history: history
        };
    })
    .factory('loadingHttpInterceptor', ['$q', 'loadingHttpList', function ($q, loadingHttpList) {
        return {
            // optional method
            'request': function (config) {
                var deferred = $q.defer();

                loadingHttpList.append(config, deferred);

                config.success = undefined;
                config.loadingStart = (new Date).getTime();
                config.loadingEnd = undefined;

                config.loadingListId = loadingHttpList.getList().length - 1;

                deferred.promise.then(function () {
                    config.success = true;
                    config.loadingEnd = (new Date).getTime();
                }, function () {
                    config.success = false;
                    config.loadingEnd = (new Date).getTime();
                });

                return config;
            },

            // optional method
            'response': function (response) {
                var config = loadingHttpList.getItem(response.config.loadingListId);
                config.deferred.resolve();
                return response;
            },

            // optional method
            'responseError': function (rejection) {
                var config = loadingHttpList.getItem(rejection.config.loadingListId);
                config.deferred.reject();
                return $q.reject(rejection);
            }
        };
    }])
;