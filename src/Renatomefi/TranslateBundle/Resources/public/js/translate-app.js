'use strict'

// Configuring $translateProvider
var sammuiTranslate = angular.module('sammui')
    .config(['$translateProvider', function ($translateProvider) {

        $translateProvider.preferredLanguage('en-us');
        $translateProvider.useLoader('translateLoader');

    }])
    .filter('getByKey', function () {
        return function (data, key) {
            var result = null;
            angular.forEach(data, function (item) {
                if (key == item.key) {
                    result = item;
                    return;
                }
            });

            return result;
        }
    });

sammuiTranslate.run([
    '$rootScope', function ($rootScope) {
        $rootScope.$on('$locationChangeSuccess', function () {
            if (typeof $rootScope.langKeysTable !== 'undefined') {
                $rootScope.langKeysTable()
            }
        });
    }
])