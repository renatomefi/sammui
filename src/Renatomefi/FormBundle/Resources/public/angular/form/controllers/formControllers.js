'use strict';

angular.module('sammui.formControllers', ['ngRoute'])
    .controller('formStart', ['$rootScope', '$scope', '$location', 'formList', 'formProtocol',
        function ($rootScope, $scope, $location, formList, formProtocol) {

            $scope.forms = {
                current: undefined,
                protocol: undefined,
                loading: undefined,
                data: undefined
            };

            $scope.loadForms = function () {
                $scope.forms.loading = true;

                formList.query({}, function (data) {
                    $scope.forms.data = data;
                }).$promise.finally(function () {
                        $scope.forms.loading = false;
                    });
            };

            $scope.startForm = function (formId) {
                formProtocol.generate({formId: formId}, function (data) {
                    $scope.continueForm(data.id);
                });
            };

            $scope.continueForm = function (protocolId) {
                $location.path('/form/start/' + protocolId);
            };

            $scope.loadForms();
        }
    ])
    .controller('formFilling', ['$rootScope', '$scope', '$route', '$routeParams', '$location', 'formProtocolManage',
        function ($rootScope, $scope, $route, $routeParams, $location, formProtocolManage) {

            $scope.protocol = {
                data: undefined,
                currentPage: $routeParams.pageId
            };

            $scope.loadProtocol = function () {
                $rootScope.loading = true;

                formProtocolManage.get(
                    {protocolId: $routeParams.protocolId},
                    function (data) {
                        $scope.protocol.data = angular.copy(data);
                    },
                    function () {
                        $location.path('/form/start');
                    })
                    .$promise.finally(function () {
                        $rootScope.loading = false;
                    });
            };

            $scope.toPage = function (pageId) {
                if (!angular.isUndefined($routeParams.pageId)) {
                    $route.updateParams({pageId: pageId});
                } else {
                    $location.path($location.path() + '/page/' + pageId);
                }
            };

            $scope.loadProtocol();

        }
    ])
    .controller('formFillingUser', ['$rootScope', '$scope', 'formProtocolUser', function ($rootScope, $scope, formProtocolUser) {

        $scope.newUser = undefined;
        $scope.loading = false;

        $scope.addUser = function (userName) {
            $scope.loading = true;

            formProtocolUser
                .add({
                    protocolId: $scope.$parent.protocol.data.id,
                    userName: userName
                }, function (data) {
                    $scope.newUser = null;
                    $scope.$parent.protocol.data.user = angular.copy(data.user);
                    $scope.$parent.protocol.data.non_user = angular.copy(data.nonUser);
                })
                .$promise.finally(function () {
                    $scope.loading = false;
                });
        };

        $scope.removeUser = function (userName) {
            $scope.loading = true;

            formProtocolUser
                .remove({
                    protocolId: $scope.$parent.protocol.data.id,
                    userName: userName
                }, function (data) {
                    $scope.$parent.protocol.data.user = angular.copy(data.user);
                    $scope.$parent.protocol.data.non_user = angular.copy(data.nonUser);
                })
                .$promise.finally(function () {
                    $scope.loading = false;
                });
        };
    }])
    .controller('formFillingComment', ['$rootScope', '$scope', 'formProtocolComment', function ($rootScope, $scope, formProtocolComment) {

        $scope.newComment = undefined;
        $scope.loading = false;

        $scope.addComment = function () {
            $scope.loading = true;

            formProtocolComment
                .add({
                    protocolId: $scope.$parent.protocol.data.id,
                    body: $scope.newComment
                }, function (data) {
                    $scope.newComment = null;
                    $scope.$parent.protocol.data.comment = angular.copy(data);
                })
                .$promise.finally(function () {
                    $scope.loading = false;
                });
        };

        $scope.removeComment = function (commentId) {
            $scope.loading = true;

            formProtocolComment
                .remove({
                    protocolId: $scope.$parent.protocol.data.id,
                    commentId: commentId
                }, function (data) {
                    $scope.$parent.protocol.data.comment = angular.copy(data);
                })
                .$promise.finally(function () {
                    $scope.loading = false;
                });
        };
    }]);
