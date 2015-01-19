'use strict';

// Declare app level module which depends on filters, and services
var sammui = angular.module('sammui', [
    'ngRoute',
    'sammui.services',
    'pascalprecht.translate',
    'xeditable',
    'mobile-angular-ui',
    'mobile-angular-ui.gestures',
    'sammui.filters',
    'sammui.directives',
    'sammui.controllers'
]).config(function ($routeProvider) {
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

// Template pre-load
sammui.run([
    '$route', '$templateCache', '$http', (function ($route, $templateCache, $http) {
        console.debug('templateCache: Starting caching all templates from routes')
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