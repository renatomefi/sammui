'use strict';

angular.module('sammui.apiAuthServices', ['ngResource', 'ngRoute'])
    // Resource factories for OAuth API
    .factory('oAuth', ['$http', 'authService', function ($http, authService) {

        // Sammui client ID and Secret, you should get one with the client:create command at symfony
        // To-do: Where should I store credentials?
        var oAuthClientId = '54d2028ceabc88600a8b4567_qss71wwodiosk84gk4gwwk8s40k48wgg0cgkw8wwkwwgkcg44';
        var oAuthClientSecret = '5o808pbhkw84kcwggocc0ogos8c44socccgc0880koggoc08sk';

        var oAuth = {};

        oAuth.getAnonymous = function () {
            $http.post('/oauth/v2/token',
                {
                    client_id: oAuthClientId,
                    client_secret: oAuthClientSecret,
                    grant_type: 'client_credentials'
                }).
                success(function (data) {
                    authService.loginConfirmed('success', function (config) {
                        config.params = {access_token: data.access_token};
                        return config;
                    });
                }).
                error(function (data, status) {
                    return status;
                });
        };

        return oAuth;

    }]);