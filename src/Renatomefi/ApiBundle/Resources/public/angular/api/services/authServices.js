'use strict';

angular.module('sammui.apiAuthServices', ['ngResource', 'ngRoute'])
    // Resource factories for OAuth API
    .factory('oauthAnonymous', ['$http', 'authService', function ($http, authService) {
        return {
            'getCredentials': function () {
                $http.post('/oauth/v2/token',
                    {
                        client_id: '54d2028ceabc88600a8b4567_qss71wwodiosk84gk4gwwk8s40k48wgg0cgkw8wwkwwgkcg44',
                        client_secret: '5o808pbhkw84kcwggocc0ogos8c44socccgc0880koggoc08sk',
                        grant_type: 'client_credentials'
                    }).
                    success(function (data) {
                        authService.loginConfirmed('success', function (config) {
                            config.params = {"access_token": data.access_token};
                            return config;
                        });
                    }).
                    error(function (data, status) {
                        return status;
                    });
            }
        };

    }]);