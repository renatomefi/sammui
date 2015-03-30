'use strict';

angular.module('sammui.protocolControllers', ['ngRoute'])
    .controller('formFilling', ['$rootScope', '$scope', '$route', '$routeParams', '$location', 'formProtocolManage',
        function ($rootScope, $scope, $route, $routeParams, $location, formProtocolManage) {

            // TODO it should be a service to store all changes!
            $scope.protocol = {
                data: undefined
            };

            $scope.loadProtocol = function () {
                $rootScope.loading = true;
                formProtocolManage.get(
                    {protocolId: $routeParams.protocolId},
                    function (data) {
                        $scope.protocol.data = angular.copy(data);
                        $scope.$broadcast('formEvent:form-protocol-loaded');
                    },
                    function () {
                        $location.path('/form');
                    })
                    .$promise.finally(function () {
                        $rootScope.loading = false;
                    });
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
    }])
    .controller('formFillingConclusion', ['$scope', 'formProtocolConclusion', function ($scope, formProtocolConclusion) {
        $scope.loading = false;

        if (!$scope.$parent.protocol.data) {
            $scope.savedConclusion = undefined;
        } else {
            $scope.savedConclusion = angular.copy($scope.$parent.protocol.data.conclusion);
        }

        $scope.$on('formEvent:form-protocol-loaded', function () {
            $scope.savedConclusion = angular.copy($scope.$parent.protocol.data.conclusion);
        });

        $scope.saveConclusion = function () {
            $scope.loading = true;
            formProtocolConclusion
                .save({
                    protocolId: $scope.$parent.protocol.data.id,
                    conclusion: $scope.$parent.protocol.data.conclusion
                }, function () {
                    $scope.savedConclusion = angular.copy($scope.$parent.protocol.data.conclusion);
                })
                .$promise.finally(function () {
                    $scope.loading = false;
                });
        };
    }])
    .controller('formFillingPagination', ['$scope', '$routeParams', '$location', '$route', function ($scope, $routeParams, $location, $route) {

        //TODO configuration file??
        var partialPath = '/bundles/form/angular/views/form/filling/partials/';
        var templatePath = '/bundles/form/angular/views/form/pages/sammui_demo/';

        //Default templates to all forms
        $scope.templates = [
            {name: 'index', url: partialPath + 'index.html', headerType: 'index'},
            {name: 'users', url: partialPath + 'user.html'},
            {name: 'comments', url: partialPath + 'comment.html'},
            {name: 'conclusion', url: partialPath + 'conclusion.html'},
        ];

        //TODO bring form server
        $scope.formPages = {
            1: {title: 'Título página Um', period: 6},
            2: {title: 'Título página Dois'}
        };

        $scope.toPage = function (pageId) {
            if (!angular.isUndefined($routeParams.pageId)) {
                $route.updateParams({pageId: pageId});
            } else {
                $location.path($location.path() + '/page/' + pageId);
            }

            if (isFinite(parseInt(pageId))) {
                $scope.currentTemplate = {name: pageId, url: templatePath + pageId + '.html', headerType: 'form'};
            } else {
                for (var i = 0, len = $scope.templates.length; i < len; i++) {
                    if (pageId === $scope.templates[i].name) {
                        $scope.selectedTemplate = $scope.currentTemplate = $scope.templates[i];
                        break;
                    }
                }
            }
        };

        //Get page from url
        $scope.toPage(($routeParams.pageId) ? $routeParams.pageId : 'index');

    }]);