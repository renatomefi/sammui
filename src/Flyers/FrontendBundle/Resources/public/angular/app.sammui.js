'use strict';

// Declare app level module which depends on filters, and services
var sammui = angular.module('sammui', [
    'ngRoute',
    'ngAnimate',
    'ngCookies',
    'LocalStorageModule',
    'angularFileUpload',
    'xeditable',
    'mobile-angular-ui',
    'mobile-angular-ui.gestures',
    'ui.unique',
    'textAngular',
    'sammui.api',
    'sammui.form',
    'sammui.translate',
    'sammui.mainFilters',
    'sammui.mainServices',
    'sammui.mainDirectives',
    'sammui.mainControllers'
]).config(function ($locationProvider, $routeProvider, $compileProvider, localStorageServiceProvider) {

    $compileProvider.debugInfoEnabled(true);

    localStorageServiceProvider.setPrefix('sammuiStorage');

    $locationProvider.html5Mode({
        enabled: false,
        requireBase: false
    });

    $routeProvider.when('/login', {
        templateUrl: '/bundles/frontend/angular/views/login.html',
        templatePreload: true,
        controller: 'oAuthLogin',
        reloadOnSearch: false
    });

    $routeProvider.when('/ui', {
        templateUrl: '/bundles/frontend/angular/views/ui.html',
        templatePreload: true,
        controller: 'UiCtrl',
        reloadOnSearch: false
    });

    $routeProvider.otherwise({redirectTo: '/login'});
});

sammui.run([
    '$rootScope', function ($rootScope) {
        // Loading spin for every route change
        $rootScope.$on('$routeChangeStart', function () {
            $rootScope.loading = true;
        });

        $rootScope.$on('$routeChangeSuccess', function () {
            $rootScope.loading = false;
        });
    }
]);

// Template pre-load
sammui.run([
    '$route', '$templateCache', '$http', function ($route, $templateCache, $http) {
        var url;
        angular.forEach($route.routes, function ($r) {
            if ($r.templatePreload) {
                if ($r.templateUrl) {
                    url = $r.templateUrl;
                    $http.get(url, {cache: $templateCache});
                }
            }
        });
    }
]);

// Xeditable configs
sammui.run(function (editableOptions) {
    editableOptions.theme = 'bs3';
});
