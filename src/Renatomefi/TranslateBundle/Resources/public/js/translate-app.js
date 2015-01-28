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