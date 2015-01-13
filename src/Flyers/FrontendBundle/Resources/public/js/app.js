'use strict';

// Declare app level module which depends on filters, and services
var sammui = angular.module('sammui', [
    'ngRoute',
    'pascalprecht.translate',
    'mobile-angular-ui',
    'mobile-angular-ui.gestures',
    'sammui.filters',
    'sammui.services',
    'sammui.directives',
    'sammui.controllers'
]).config(function ($routeProvider) {
    $routeProvider.when('/login', {
        templateUrl: '/bundles/frontend/partials/login.html',
        controller: 'Login',
        reloadOnSearch: false,
        preload: true
    });
    $routeProvider.when('/ui', {
        templateUrl: '/bundles/frontend/partials/ui.html',
        controller: 'UiCtrl',
        reloadOnSearch: false,
        preload: true
    });
    $routeProvider.when('/view1', {
        templateUrl: '/bundles/frontend/partials/partial1.html',
        controller: 'MyCtrl1',
        reloadOnSearch: false,
        preload: true
    });
    $routeProvider.when('/view2', {
        templateUrl: '/bundles/frontend/partials/partial2.html',
        controller: 'MyCtrl2',
        reloadOnSearch: false,
        preload: true
    });
    $routeProvider.otherwise({redirectTo: '/login'});
});

sammui.run([
    '$route', '$templateCache', '$http', (function ($route, $templateCache, $http) {
        console.debug('templateCache: Starting caching all templates from routes')
        var url;
        for (var i in $route.routes) {
            if ($route.routes[i].preload) {
                if (url = $route.routes[i].templateUrl) {
                    $http.get(url, {cache: $templateCache});
                }
            }
        }
    })
]);