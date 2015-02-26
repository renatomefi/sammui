'use strict';

angular.module('sammui.apiHttpServices', ['ngResource', 'ngRoute'])

    .filter('loadingProgressBar', function () {
        return function (loadingHttpList, type) {

            var items = loadingHttpList;

            var total = items.length;
            var completedSuccess = 0;
            var completedError = 0;

            angular.forEach(items, function (value, key) {
                if (!angular.isUndefined(value.success)) {
                    if (value.success === true) {
                        completedSuccess++;
                        console.log('completedSuccess');
                    } else {
                        completedError++;
                    }
                }
            });
            return {total: total, completedS: completedSuccess, completedE: completedError};
        }
    })

    .service('loadingHttpList', function ($q, $timeout) {
        var all;

        var service = {

            list: [],
            history: [],
            completed: 0,
            completedError: 0,
            completedPercent: 0,
            completedErrorPercent: 0,
            totalPercent: 0,

            append: function (config) {
                var a = {
                    config: config
                };
                service.list.push(a);
                service.refreshCounters();

                var listPromises = [];

                if (all) {
                    listPromises.push(all.promise);
                }
                listPromises.push(config._loadingDefer.promise);

                config._loadingDefer.promise.finally(function () {
                    service.refreshCounters();
                });

                all = $q.all(listPromises).finally(function () {
                    var exists = service.list.some(function (item) {
                        return (item.config.loadingEnd === undefined);
                    });

                    if (!exists) {
                        $timeout(service.clear, 1000);
                    }
                });


            },
            refreshCounters: function () {
                service.totalPercent = (((service.completed + service.completedError) * 100) / service.getList().length);
                service.completedPercent = ((service.completed * 100) / service.getList().length);
                service.completedErrorPercent = ((service.completedError * 100) / service.getList().length);
            },
            getItem: function (id) {
                return service.list[id];
            },
            clear: function () {
                service.history = service.history.concat(angular.copy(service.list));
                service.list.splice(0, service.list.length);
                service.completed = 0;
                service.completedError = 0;
                service.completedPercent = 0;
                service.completedErrorPercent = 0;
                service.totalPercent = 0;
                service.all = null;
            },
            getList: function () {
                return service.list;
            },
            getHistory: function () {
                return service.history;
            }
        };

        return service;
    })

    .factory('loadingHttpInterceptor', ['$q', 'loadingHttpList', function ($q, loadingHttpList) {
        return {
            // optional method
            'request': function (config) {
                var deferred = $q.defer();

                config._loadingDefer = deferred;
                config.success = undefined;
                config.loadingStart = (new Date).getTime();
                config.loadingEnd = undefined;

                deferred.promise.then(function () {
                    loadingHttpList.completed++;
                    config.success = true;
                }, function () {
                    loadingHttpList.completedError++;
                    config.success = false;
                });

                deferred.promise.finally(function () {
                    config.loadingEnd = (new Date).getTime();
                });

                loadingHttpList.append(config);
                return config;
            },

            // optional method
            'response': function (response) {
                //config.deferred.resolve();
                //var config = loadingHttpList.getItem(response.config.loadingListId);
                response.config._loadingDefer.resolve();
                return response;
            },

            // optional method
            'responseError': function (rejection) {
                rejection.config._loadingDefer.reject();
                return $q.reject(rejection);
            }
        };
    }])
;