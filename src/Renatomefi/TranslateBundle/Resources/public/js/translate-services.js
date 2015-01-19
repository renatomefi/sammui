'use strict';

angular.module('sammui.translateServices', ['ngResource']).
    factory('translateLoader', ['$q', '$timeout', '$resource', function ($q, $timeout, $resource) {

        return function (options) {
            var deferred = $q.defer(),
                translations;

            var Languages = $resource('/l10n/manage/langs/:lang', {lang: '@lang'});

            console.debug('Downloading translate');
            var lang = Languages.get({lang:options.key}, function(){
                console.debug(lang);
            });

            if (options.key === 'en-us') {
                translations = {
                    "index-title-sidebar-left": 'Srsly!',
                    "index-title-sidebar-right": 'YlsrS!'
                };
            } else {
                translations = {
                    "index-title-sidebar-left": 'Ernsthaft!',
                    "index-title-sidebar-right": 'Tfahtsnre!'
                };
            }

            $timeout(function () {
                deferred.resolve(translations);
            }, 2000);

            return deferred.promise;
        };
    }]);