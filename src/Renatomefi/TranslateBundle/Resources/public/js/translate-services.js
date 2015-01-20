'use strict';

angular.module('sammui.translateServices', ['ngResource'])
    .factory('translateLangs', function ($resource) {
        return $resource('/l10n/manage/langs/:lang');
    }).
    factory('translateLoader', ['$q', 'translateLangs', function ($q, translateLangs) {
        return function (options) {
            var deferred = $q.defer(), translations = new Object();

            console.debug('Downloading translate for ' + options.key);
            var lang = translateLangs.get({lang: options.key}, function () {
                lang.translations.forEach(function (t) {
                    if (t) {
                        translations[t.key] = t.value;
                    }
                })

                deferred.resolve(translations);
            });

            return deferred.promise;
        };
    }]);