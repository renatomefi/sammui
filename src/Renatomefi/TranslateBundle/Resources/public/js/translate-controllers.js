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
            $scope.langs = translateLangs.query();
        }
    ]);
