'use strict';

angular.module('sammui.translateServices', ['ngResource']).
    factory('translateLoader', ['$q', '$timeout', '$resource', function ($q, $timeout, $resource) {

        return function (options) {
            var deferred = $q.defer(), translations = new Object();

            var Languages = $resource('/l10n/manage/langs/:lang', {lang: '@lang'});

            console.debug('Downloading translate');
            var lang = Languages.get({lang: options.key}, function () {
                lang.translations.forEach(function(t){
                    if (t) {
                        translations[t.key] = t.value;
                    }
                })

                deferred.resolve(translations);
            });

            return deferred.promise;
        };
    }]);