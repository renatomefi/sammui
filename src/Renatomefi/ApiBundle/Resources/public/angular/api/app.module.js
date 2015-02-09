'use strict';

// Configuring $translateProvider
var sammuiApi = angular.module('sammui.api', [
    'http-auth-interceptor',
    'sammui.apiAuthServices'
]);

sammuiApi.run([
    '$rootScope', '$location', 'oauthAnonymous', (function ($rootScope, $location, oauthAnonymous) {
        $rootScope.$on('event:auth-loginRequired', function () {
            oauthAnonymous.getCredentials();
        });
        $rootScope.$on('event:auth-forbidden', function () {
            $location.path("/login");
        });
    })
]);