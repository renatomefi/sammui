'use strict';

// Configuring $translateProvider
var sammuiTranslate = angular.module('sammui.translate', [
    'pascalprecht.translate',
    'sammui.translateServices',
    'sammui.translateControllers'
]).config(['$translateProvider', function ($translateProvider) {
    //$translateProvider.determinePreferredLanguage();
    $translateProvider.preferredLanguage('en-us');
    //var preferred = $translateProvider.preferredLanguage();
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

sammuiTranslate.run(function ($rootScope, $translate, translateLangsInfo) {
    $rootScope.modalLangChoose = function () {
        $rootScope.loading = true;
        $rootScope.modalLangItems = translateLangsInfo.query(function () {
            $rootScope.Ui.turnOn('modalLangChoose');
            $rootScope.loading = false;
        });
    };
    $rootScope.appLangChoose = function (lang) {
        $translate.use(lang);
        //$translate.refresh();
    };
});