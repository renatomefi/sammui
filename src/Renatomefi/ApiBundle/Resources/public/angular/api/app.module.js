'use strict';

// Configuring $translateProvider
var sammuiApi = angular.module('sammui.api', [
    'http-auth-interceptor',
    'sammui.apiAuthServices'
]);

sammuiApi.run([
    '$rootScope', 'oauthAnonymous', (function ($rootScope, oauthAnonymous) {
        $rootScope.$on('event:auth-loginRequired', function () {
            oauthAnonymous.getCredentials();
        });
    })
]);