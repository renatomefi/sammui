'use strict';


// Declare app level module which depends on filters, and services
var myApp = angular.module('myApp', ['ngRoute'])
//var app = angular.module('myApp', ['ngRoute', 'myApp.filters', 'myApp.services', 'myApp.directives', 'myApp.controllers'])
    .config(['$routeProvider',
        function($routeProvider) {
            $routeProvider.
                when('/phones', {
                    templateUrl: 'partials/phone-list.html',
                    controller: 'PhoneListCtrl'
                }).
                when('/phones/:phoneId', {
                    templateUrl: 'partials/phone-detail.html',
                    controller: 'PhoneDetailCtrl'
                }).
                otherwise({
                    redirectTo: '/phones'
                });
        }]);

//myApp.factory('getSettings', ['$http', '$q', function ($http, $q) {
//    return {
//        //Code edited to create a function as when you require service it returns object by default so you can't return function directly. That's what understand...
//        getSetting: function (type) {
//            var q = $q.defer();
//            $http.get('models/settings.json').success(function (data) {
//                q.resolve(function () {
//                    var settings = jQuery.parseJSON(data);
//                    return settings[type];
//                });
//            });
//            return q.promise;
//        }
//    }
//}]);

//config(function ($routeProvider, $locationProvider) {
//    $routeProvider.when('/login', {templateUrl: '/bundles/frontend/partials/login.html', controller: 'Login'});
//    $routeProvider.when('/view1', {templateUrl: '/bundles/frontend/partials/partial1.html', controller: 'MyCtrl1'});
//    $routeProvider.when('/view2', {templateUrl: '/bundles/frontend/partials/partial2.html', controller: 'MyCtrl2'});
//    $routeProvider.otherwise({redirectTo: '/login'});
//});
