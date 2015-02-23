'use strict';

var sammuiApi = angular.module('sammui.api', [
    'http-auth-interceptor',
    'sammui.apiAuthServices',
    'sammui.apiAuthControllers'
]);

sammuiApi.config(['$httpProvider', function($httpProvider) {
    $httpProvider.interceptors.push('oAuthHttpInjector');
}]);

sammuiApi.run([
    '$rootScope', '$location', 'oAuth', 'oAuthSession', (function ($rootScope, $location, oAuth, oAuthSession) {

        oAuth.getInfo(null, true);

        $rootScope.currentUser = oAuthSession;

        $rootScope.$on('event:auth-loginRequired', function () {
            oAuth.beAnonymous();
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

    })
]);