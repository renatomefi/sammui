'use strict';

// Declare app level module which depends on filters, and services
var sammui = angular.module('sammui', [
    'ngRoute',
    'sammui.services',
    'xeditable',
    'mobile-angular-ui',
    'mobile-angular-ui.gestures',
    'sammui.translate',
    'sammui.filters',
    'sammui.directives',
    'sammui.controllers'
]).config(function ($locationProvider, $routeProvider, $compileProvider) {

    $compileProvider.debugInfoEnabled(true);

    $locationProvider.html5Mode({
        enabled: false,
        requireBase: false
    });

    $routeProvider.when('/login', {
        templateUrl: '/bundles/frontend/partials/login.html',
        templatePreload: true,
        controller: 'Login',
        reloadOnSearch: false
    });
    $routeProvider.when('/ui', {
        templateUrl: '/bundles/frontend/partials/ui.html',
        templatePreload: true,
        controller: 'UiCtrl',
        reloadOnSearch: false
    });
    $routeProvider.when('/view1', {
        templateUrl: '/bundles/frontend/partials/partial1.html',
        templatePreload: true,
        controller: 'MyCtrl1',
        reloadOnSearch: false
    });
    $routeProvider.when('/view2', {
        templateUrl: '/bundles/frontend/partials/partial2.html',
        templatePreload: true,
        controller: 'MyCtrl2',
        reloadOnSearch: false
    });
    $routeProvider.otherwise({redirectTo: '/login'});
});


sammui.run([
    '$rootScope', (function ($rootScope) {
        // Loading spin for every route change
        $rootScope.$on('$routeChangeStart', function () {
            $rootScope.loading = true;
        });

        $rootScope.$on('$routeChangeSuccess', function () {
            $rootScope.loading = false;
        });
    })
]);

// Template pre-load
sammui.run([
    '$route', '$templateCache', '$http', (function ($route, $templateCache, $http) {
        var url;
        for (var i in $route.routes) {
            if ($route.routes[i].templatePreload) {
                if (url = $route.routes[i].templateUrl) {
                    $http.get(url, {cache: $templateCache});
                }
            }
        }
    })
]);

// Xeditable configs
sammui.run(function (editableOptions) {
    editableOptions.theme = 'bs3';
});
