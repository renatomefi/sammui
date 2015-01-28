'use strict'

angular.module('sammui.translateControllers', ['ngRoute'])
    // Routes
    .config(function ($routeProvider) {
        $routeProvider.when('/l10n/admin', {
            templateUrl: '/l10n/admin',
            templatePreload: false,
            controller: 'TranslateKeysController',
            reloadOnSearch: false
        });
    })
    .controller('TranslateKeysController',
    ['$rootScope', '$scope', '$q', '$window', '$location', '$filter', '$translate', 'translateLangs', 'translateLangsKeys',
        function ($rootScope, $scope, $q, $window, $location, $filter, $translate, translateLangs, translateLangsKeys) {

            console.debug('entrei controller');

            $scope.loadLangs = function () {
                translateLangs.query({}, function (data) {

                    $scope.translate.langs = data;

                    if ($location.search()['lang']) {
                        $scope.langKeysTable();
                    }

                    return data;
                });
            };

            $scope.translate = {
                'table': false,
                'currentLang': null,
                'langs': $scope.loadLangs()
            };

            $scope.langPublish = function () {
                $translate.refresh()
            };

            $scope.langChange = function (lang) {
                //$route.updateParams({lang: lang});
                $location.search('lang', lang);
            };

            $scope.langKeysTable = function (lang, reload) {

                lang = lang || $location.search()['lang'];
                reload = reload || false;

                if (angular.isUndefined(lang))
                    return;

                var language = $filter('getByKey')($scope.translate.langs, lang);

                if (language) {
                    $scope.translate.currentLang = language;

                    if (!reload) {
                        $scope.translate.langKeys = language.translations;
                        $scope.translate.table = true;
                        return;
                    }
                }

                $rootScope.loading = true;

                var langKeys = translateLangsKeys.query({lang: lang},
                    function () {
                        $scope.translate.langKeys = langKeys;
                        $scope.translate.table = true;
                        $rootScope.loading = false;
                    },
                    function (errorResponse) {
                        $scope.error = errorResponse;
                        $scope.Ui.turnOn("modalError");
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
                $scope.translateLangKeyFormEditableKey = (translation.id) ? true : false;
            };

            $scope.saveLangKey = function (data, translation, index) {
                var post = $q.defer();
                var type = (angular.isUndefined(translation.id)) ? 'save' : 'update';

                translateLangsKeys[type](
                    {
                        lang: $location.search()['lang'],
                        keys: data.key,
                        value: data.value
                    },
                    function (response) {
                        if (!angular.isUndefined(response.id)) {
                            $scope.translate.langKeys[index] = response;
                            post.resolve();
                        }
                    },
                    function (error) {
                        $rootScope.$emit('errorResourceReq', error);
                        post.reject(error.statusText);
                    }
                );

                return post.promise;
            };

            $scope.deleteLangKey = function (index) {
                var langTranslation = $scope.translate.langKeys[index];

                if (typeof langTranslation.id === 'undefined') {
                    $scope.translate.langKeys.splice(index, 1);
                    return;
                }

                var post = $q.defer();

                translateLangsKeys.delete(
                    {
                        lang: $location.search()['lang'],
                        keys: langTranslation.key
                    },
                    function (response) {
                        if (response.ok) {
                            $scope.translate.langKeys.splice(index, 1);
                            post.resolve();
                        } else {
                            $rootScope.$emit('errorResourceReq', response);
                            post.reject(response);
                        }
                    });

                return post;
            };

            $scope.$on('$locationChangeSuccess', function (e) {
                $scope.langKeysTable();
            });

            $rootScope.$on('errorResourceReq', function (e, errorResponse) {
                $scope.error = errorResponse;
                $scope.error.message = 'error-translate-delete-' + errorResponse.status;
                $scope.Ui.turnOn("modalError");
            });

        }
    ]);
