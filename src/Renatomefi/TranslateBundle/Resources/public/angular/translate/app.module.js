'use strict';

// Configuring $translateProvider
var sammuiTranslate = angular.module('sammui.translate', [
    'pascalprecht.translate',
    'sammui.translateServices',
    'sammui.translateControllers'
]).config(['$translateProvider', function ($translateProvider) {
    //$translateProvider.preferredLanguage('en-us');
    $translateProvider.useLoader('translateLoader');
}]);

sammuiTranslate.filter('getByKey', function () {
    return function (data, key) {
        var result = null;
        angular.forEach(data, function (item) {
            if (key === item.key) {
                result = item;
                return;
            }
        });

        return result;
    };
});

sammuiTranslate.run(function ($rootScope, $translate, translateLangsInfo, localStorageService) {

    if (localStorageService.get('preferredLanguage')) {
        $translate.use(localStorageService.get('preferredLanguage'));
    } else {
        // fallback language
        $translate.use('en-us');
    }

    $rootScope.modalLangChoose = function () {
        $rootScope.loading = true;
        $rootScope.modalLangItems = translateLangsInfo.query(function () {
            $rootScope.Ui.turnOn('modalLangChoose');
            $rootScope.loading = false;
        });
    };
    $rootScope.appLangChoose = function (lang) {
        $translate.use(lang);
        localStorageService.set('preferredLanguage', lang);
    };
});