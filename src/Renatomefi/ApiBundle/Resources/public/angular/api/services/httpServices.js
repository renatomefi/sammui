'use strict';

angular.module('sammui.apiHttpServices', ['ngResource', 'ngRoute'])

    .service('loadingHttpList', function () {
        var list = [];

        return {
            append: function (config, deferred) {
                list.push({
                    config: config,
                    deferred: deferred
                });
            },
            clear: function (reason) {
                if (reason) {
                    for (var i = 0; i < list.length; ++i) {
                        list[i].deferred.reject(reason);
                    }
                }
                list = [];
            },
            getList: function () {
                return list;
            }
        };
    })
    .factory('loadingHttpInterceptor', ['$q', 'loadingHttpList', function ($q, loadingHttpList) {
        return {
            // optional method
            'request': function (config) {
                loadingHttpList.append(config);
                config.loadingListId = loadingHttpList.getList().length - 1;
                console.debug('loadingHttpInterceptor:request', config);
                return config;
            },

            // optional method
            'response': function (response) {
                console.debug('loadingHttpInterceptor:response');
                return response;
            },

            // optional method
            'responseError': function (rejection) {
                console.debug('loadingHttpInterceptor:responseError');
                return $q.reject(rejection);
            }
        };
    }])
;