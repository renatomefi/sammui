'use strict'

angular.module('sammui.translateControllers', ['ngRoute'])
    .filter('test', function () {

    })
    // Routes
    .config(function ($routeProvider) {
        $routeProvider.when('/l10n/admin/:lang?', {
            templateUrl: '/l10n/admin',
            templatePreload: false,
            controller: 'TranslateAdmin',
            reloadOnSearch: false
        });
    })
    .controller('TranslateAdmin', ['$rootScope', '$scope', '$window', '$filter', '$routeParams', 'translateLangs', 'translateLangsKeys',
        function ($rootScope, $scope, $window, $filter, $routeParams, translateLangs, translateLangsKeys) {
            $scope.translate = new Object();

            $scope.translate.table = false;

            $scope.translate.langs = translateLangs.query({}, function () {
                if ($routeParams.lang) {
                    $scope.langKeysTable($routeParams.lang);
                }
            });

            $scope.langKeysTable = function (lang, reload) {
                $rootScope.loading = true;

                reload = reload || false;

                var language = $filter('getByKey')($scope.translate.langs, lang);

                if (language && reload === false) {
                    $scope.translate.langs.keys = language.translations;
                    $scope.translate.table = true;
                    $rootScope.loading = false;
                    return;
                }

                var langKeys = translateLangsKeys.query({lang: lang}, function () {
                    $scope.translate.langs.keys = langKeys;
                    $scope.translate.table = true;
                    $rootScope.loading = false;
                });
            };

            $scope.addLangKey = function () {
                $scope.inserted = {
                    key: null,
                    value: null
                };
                $scope.translate.langs.keys.push($scope.inserted);
            };

            $scope.editCheck = function (translation) {
                console.debug('onshow check');
                if (translation.id) {
                    console.debug('true');
                    $scope.translateLangKeyFormEditableKey = true;
                } else {
                    console.debug('false');
                    $scope.translateLangKeyFormEditableKey = false;
                }
            };

            $scope.deleteLangKey = function (index) {
                var langTranslation = $scope.translate.langs.keys[index];

                if (typeof langTranslation.id === 'undefined') {
                    $scope.translate.langs.keys.splice(index, 1);
                    return;
                }
                translateLangsKeys.delete({lang: langTranslation.language.key, key: langTranslation.key},
                    function (response) {
                        $scope.translate.langs.keys.splice(index, 1);
                    },
                    function (errorResponse) {
                        $scope.error = errorResponse;
                        $scope.Ui.turnOn("modalError");
                    });
            };
        }
    ]);
