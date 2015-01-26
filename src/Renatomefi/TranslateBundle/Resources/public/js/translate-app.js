'use strict';

// Configuring $translateProvider
var sammuiTranslate = angular.module('sammui.translate', [
    'pascalprecht.translate',
    'sammui.translateServices',
    'sammui.translateControllers',
]).config(['$translateProvider', function ($translateProvider) {

    $translateProvider.preferredLanguage('en-us');
    $translateProvider.useLoader('translateLoader');

}]);

sammuiTranslate.filter('getByKey', function () {
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
    '$rootScope', '$route', function ($rootScope, $route) {
        $rootScope.$on('$locationChangeSuccess', function () {
            if (!angular.isDefined($route.current.$$route))
                return;

            if (angular.isFunction($rootScope.langKeysTable) &&
                $route.current.$$route.controller === 'TranslateKeysController') {
                $rootScope.langKeysTable();
            }
        });
    }
]);