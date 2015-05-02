'use strict';

angular.module('sammui.apiAuthServices', ['ngResource', 'ngRoute', 'ngCookies'])
    .factory('oAuthHttpInjector', ['oAuthSession', function (oAuthSession) {
        return {
            request: function (config) {
                if (oAuthSession.access_token && !config.headers.Authorization && !config.ignoreAuthModule) {
                    config.headers.Authorization = 'Bearer ' + oAuthSession.access_token;
                }
                return config;
            }
        };
    }])
    .service('oAuthSession', ['$rootScope', '$cookies', '$cookies', function ($rootScope, $cookies) {

        this.create = function (userInfo) {
            if (userInfo.access_token) {
                $cookies.access_token = userInfo.access_token;
            }
            if (userInfo.refresh_token) {
                $cookies.refresh_token = userInfo.refresh_token;
            }
            this.access_token = $cookies.access_token;
            this.refresh_token = $cookies.refresh_token;
            this.authenticated = userInfo.authenticated;
            this.authenticated_fully = userInfo.authenticated_fully;
            this.authenticated_anonymously = userInfo.authenticated_anonymously;
            this.role_user = userInfo.role_user;
            this.role_admin = userInfo.role_admin;
            this.role_anonymous = userInfo.role_anonymous;
            this.client = userInfo.client || null;
            this.username = (userInfo.user) ? userInfo.user.username : null;
            this.email = (userInfo.user) ? userInfo.user.email : null;
            this.roles = (userInfo.user) ? userInfo.user.roles : null;

            $rootScope.$broadcast('event:auth-sessionCreated');
        };

        this.destroy = function () {
            delete $cookies.access_token;
            delete $cookies.refresh_token;
            this.access_token = null;
            this.refresh_token = null;
            this.authenticated = null;
            this.authenticated_fully = null;
            this.authenticated_anonymously = null;
            this.role_user = null;
            this.role_admin = null;
            this.role_anonymous = null;
            this.client = null;
            this.username = null;
            this.email = null;
            this.roles = null;

            $rootScope.$broadcast('event:auth-sessionDestroyed');
        };

        return this;
    }])
    // Resource factories for OAuth API
    .factory('oAuth', ['$http', '$rootScope', '$cookies', '$document', 'authService', 'oAuthSession', function ($http, $rootScope, $cookies, $document, authService, oAuthSession) {

        // Sammui client ID and Secret, you should get one with the client:create command at symfony
        var oAuthClientId;
        var oAuthClientSecret;

        angular.forEach($document.find('meta'), function (meta) {
            if (meta.name === 'sammui-oauth2-client-id') {
                oAuthClientId = meta.content;
            }
            if (meta.name === 'sammui-oauth2-client-secret') {
                oAuthClientSecret = meta.content;
            }
        });

        var oAuth = {};

        /**
         * @description
         * Check if the user is authenticated
         *
         * @returns {boolean}
         */
        oAuth.isAuthenticated = function () {
            return !!oAuthSession.username;
        };

        /**
         * @description
         * Be careful, if the user is authenticated it will also be anonymous
         *
         * @returns {boolean}
         */
        oAuth.isAnonymous = function () {
            return !!oAuthSession.authenticated_anonymously;
        };

        /**
         * @param oAuthResponse
         * @param createSession Want to already register it at oAuthSession?
         * @returns {*}
         */
        oAuth.getInfo = function (oAuthResponse, createSession) {
            var userInfo = null;
            createSession = createSession || false;

            var getInfoHeaders = {};

            if (oAuthResponse && oAuthResponse.access_token) {
                getInfoHeaders.Authorization = 'Bearer ' + oAuthResponse.access_token;
            }

            $http.get('/api/user/info',
                {
                    headers: getInfoHeaders
                })
                .success(function (data) {
                    userInfo = data;
                    userInfo.access_token = (oAuthResponse) ? oAuthResponse.access_token : null;
                    userInfo.refresh_token = (oAuthResponse) ? oAuthResponse.refresh_token : null;
                    userInfo.user = data.user || null;

                    if (true === createSession) {
                        oAuthSession.create(userInfo);
                    }
                });

            return userInfo;
        };

        oAuth.logout = function (force) {

            var forceLogout = function () {
                $cookies.remove('SMSESSID');
                $cookies.remove('PHPSESSID');
                $cookies.remove('access_token');
                $cookies.remove('refresh_token');
                oAuthSession.destroy();
                $rootScope.$broadcast('event:auth-logoutForced');
            };

            $http.get('/logout').success(function () {
                //if (!data.user) {
                oAuthSession.destroy();
                $rootScope.$broadcast('event:auth-logoutSuccess');
                //} else {
                //    $rootScope.$broadcast('event:auth-logoutError');
                //}
            }).error(function () {
                $rootScope.$broadcast('event:auth-logoutReqError');
            }).finally(function () {
                if (force) {
                    forceLogout();
                }
            });

        };

        oAuth.refreshToken = function (refreshToken, isRetry) {
            isRetry = isRetry || false;
            return $http.post('/oauth/v2/token',
                {
                    client_id: oAuthClientId,
                    client_secret: oAuthClientSecret,
                    refresh_token: refreshToken,
                    grant_type: 'refresh_token'
                }, {
                    ignoreAuthModule: true
                })
                .success(function (data) {
                    oAuth.getInfo(data, true);

                    authService.loginConfirmed('success', function (config) {
                        config.headers.Authorization = 'Bearer ' + data.access_token;
                        return config;
                    });
                })
                .error(function (data, status) {
                    if (status === 400 && isRetry === false) {
                        console.log('Cleaning invalid credentials');
                        oAuth.logout(true);
                        oAuth.beAnonymous();
                    }
                    $rootScope.$broadcast('event:auth-loginFail', data, status);
                    return data;
                });
        };

        oAuth.beAnonymous = function () {
            $http.post('/oauth/v2/token',
                {
                    client_id: oAuthClientId,
                    client_secret: oAuthClientSecret,
                    grant_type: 'client_credentials'
                }, {
                    ignoreAuthModule: true
                })
                .success(function (data) {
                    oAuth.getInfo(data, true);

                    authService.loginConfirmed('success', function (config) {
                        config.headers.Authorization = 'Bearer ' + data.access_token;
                        return config;
                    });
                })
                .error(function (data, status) {
                    return status;
                });
        };

        oAuth.beAuthenticated = function (data) {
            return $http.post('/oauth/v2/token',
                {
                    client_id: oAuthClientId,
                    client_secret: oAuthClientSecret,
                    username: data.username,
                    password: data.password,
                    grant_type: 'password'
                }, {
                    ignoreAuthModule: true
                })
                .success(function (data) {
                    oAuth.getInfo(data, true);

                    authService.loginConfirmed('success', function (config) {
                        config.headers.Authorization = 'Bearer ' + data.access_token;
                        return config;
                    });
                })
                .error(function (data, status) {
                    $rootScope.$broadcast('event:auth-loginFail', data, status);
                    return data;
                });
        };

        oAuth.requireAuthentication = function () {
            if (oAuthSession.refresh_token) {
                oAuth.refreshToken(oAuthSession.refresh_token);
            } else {
                oAuth.beAnonymous();
            }
        };

        return oAuth;

    }])
;