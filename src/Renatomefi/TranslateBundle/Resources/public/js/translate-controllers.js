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
    .controller('TranslateAdmin', ['$rootScope', '$scope', '$window', '$location', '$filter', '$routeParams', 'translateLangs', 'translateLangsKeys',
        function ($rootScope, $scope, $window, $location, $filter, $routeParams, translateLangs, translateLangsKeys) {
            $scope.translate = new Object();

            $scope.translate.table = false;

            $scope.translate.langs = translateLangs.query({}, function () {
                if ($location.search()['lang']) {
                    $rootScope.langKeysTable();
                }
            });

            $scope.langChange = function (lang) {
                $location.search('lang', lang);
            }

            $rootScope.langKeysTable = function (lang, reload) {
                $rootScope.loading = true;

                lang = lang || $location.search()['lang'];
                reload = reload || false;

                var language = $filter('getByKey')($scope.translate.langs, lang);

                if (language && reload === false) {
                    $scope.translate.langKeys = language.translations;
                    $scope.translate.table = true;
                    $rootScope.loading = false;
                    return;
                }

                var langKeys = translateLangsKeys.query({lang: lang}, function () {
                    $scope.translate.langKeys = langKeys;
                    $scope.translate.table = true;
                    $rootScope.loading = false;
                });
            };

            $scope.addLangKey = function () {
                $scope.inserted = {
                    key: null,
                    value: null
                };
                $scope.translate.langKeys.push($scope.inserted);
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
                var langTranslation = $scope.translate.langKeys[index];

                if (typeof langTranslation.id === 'undefined') {
                    $scope.translate.langKeys.splice(index, 1);
                    return;
                }
                console.debug(langTranslation);
                translateLangsKeys.delete({lang: langTranslation.language.key, key: langTranslation.key},
                    function (response) {
                        $scope.translate.langKeys.splice(index, 1);
                    },
                    function (errorResponse) {
                        $scope.error = errorResponse;
                        $scope.Ui.turnOn("modalError");
                    });
            };
        }
    ]);
