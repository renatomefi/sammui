'use strict';

var sammuiApi = angular.module('sammui.api', [
    'http-auth-interceptor',
    'sammui.apiHttpServices',
    'sammui.apiAuthServices',
    'sammui.apiAuthControllers',
    'sammui.apiHttpControllers'
]);

sammuiApi.config(['$httpProvider', function ($httpProvider) {
    $httpProvider.interceptors.push('oAuthHttpInjector');
    $httpProvider.interceptors.push('loadingHttpInterceptor');
}]);

sammuiApi.config(function ($locationProvider, $routeProvider) {
    $routeProvider.when('/admin/logs', {
        templateUrl: '/bundles/api/angular/views/http/logs.html',
        templatePreload: true,
        controller: 'httpLogs',
        reloadOnSearch: false
    });
});

sammuiApi.run([
    '$rootScope', '$location', 'oAuth', 'oAuthSession', 'loadingHttpList',
    function ($rootScope, $location, oAuth, oAuthSession, loadingHttpList) {

        oAuth.getInfo(null, true);

        $rootScope.currentUser = oAuthSession;

        $rootScope.loadingList = loadingHttpList;

        $rootScope.$on('event:auth-loginRequired', function () {
            oAuth.requireAuthentication();
        });
        $rootScope.$on('event:auth-forbidden', function () {
            $location.path("/login");
            $rootScope.Ui.turnOn('modalForbidden');
        });
        $rootScope.$on('event:auth-loginConfirmed', function () {
        });
        $rootScope.$on('event:auth-sessionCreated', function () {
        });
        $rootScope.$on('event:auth-sessionDestroyed', function () {
        });

    }
]);