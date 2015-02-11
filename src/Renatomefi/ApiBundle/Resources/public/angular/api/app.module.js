'use strict';

// Configuring $translateProvider
var sammuiApi = angular.module('sammui.api', [
    'http-auth-interceptor',
    'sammui.apiAuthServices'
]);

sammuiApi.run([
    '$rootScope', '$location', 'oAuth', (function ($rootScope, $location, oAuth) {
        $rootScope.$on('event:auth-loginRequired', function () {
            oAuth.getAnonymous();
        });
        $rootScope.$on('event:auth-forbidden', function () {
            $location.path("/login");
        });
    })
]);