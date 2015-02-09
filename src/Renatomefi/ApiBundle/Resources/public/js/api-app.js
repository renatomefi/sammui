'use strict';

// Configuring $translateProvider
var sammuiApi = angular.module('sammui.api', [
    'http-auth-interceptor'
]);

sammuiApi.run([
    '$rootScope', '$http', 'authService', (function ($rootScope, $http, authService) {

        $rootScope.$on('event:auth-loginRequired', function () {
            $http.post('/oauth/v2/token',
                {
                    client_id: '54d2028ceabc88600a8b4567_qss71wwodiosk84gk4gwwk8s40k48wgg0cgkw8wwkwwgkcg44',
                    client_secret: '5o808pbhkw84kcwggocc0ogos8c44socccgc0880koggoc08sk',
                    grant_type: 'client_credentials'
                }).
                success(function (data, status, headers, config) {
                    console.log('auth', data);
                    authService.loginConfirmed('success', function (config) {
                        config.params = {"access_token": data.access_token};
                        return config;
                    });
                }).
                error(function (data, status, headers, config) {
                    console.error('OAuth2 login error');
                });
        });
    })
]);