'use strict'

angular.module('sammui.translateControllers', ['ngRoute'])
    // Routes
    .config(function ($routeProvider) {
        $routeProvider.when('/l10n/admin', {
            templateUrl: '/l10n/admin',
            templatePreload: false,
            controller: 'TranslateAdmin',
            reloadOnSearch: false
        });
    })
    .controller('TranslateAdmin', ['$rootScope', '$scope', '$window', 'translateLangs', 'translateLangsKeys',
        function ($rootScope, $scope, $window, translateLangs, translateLangsKeys) {
            $scope.translate = new Object();

            $scope.translate.langs = translateLangs.query();

            $scope.translate.table = false;

            $scope.langKeysTable = function (lang) {
                $rootScope.loading = true;
                var langKeys = translateLangsKeys.query({lang: lang}, function () {
                    $scope.translate.langs.keys = langKeys;
                    $rootScope.loading = false;
                    $scope.translate.table = true;
                });
            };

            $scope.deleteLangKey = function (index) {
                var langTranslation = $scope.translate.langs.keys[index];
                translateLangsKeys.delete({lang: langTranslation.language.key, key: langTranslation.key},
                    function (successResult) {
                        $scope.translate.langs.keys.splice(index, 1);
                    },
                    function (errorResult) {
                        console.debug(errorResult);
                        $scope.error = errorResult;

                        $scope.Ui.turnOn("modalError");
                    });
            };
        }
    ]);
