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

                formList.query({},
                    function (data) {
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
                $location.path('/form/' + protocolId);
            };

            $scope.loadForms();
        }
    ])
    .controller('formFilling', ['$rootScope', '$scope', '$route', '$routeParams', '$location', 'formProtocolManage',
        function ($rootScope, $scope, $route, $routeParams, $location, formProtocolManage) {

            $scope.protocol = {
                data: undefined
            };

            $scope.loadProtocol = function () {
                $rootScope.loading = true;

                formProtocolManage.get(
                    {protocolId: $routeParams.protocolId},
                    function (data) {
                        $scope.protocol.data = angular.copy(data);
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
    .controller('formFillingPagination', ['$scope', '$routeParams', '$location', '$route', function ($scope, $routeParams, $location, $route) {

        var partialPath = '/bundles/form/angular/views/form/filling/partials/';
        var templatePath = '/bundles/form/angular/views/form/pages/sammui_demo/';

        $scope.templates = [
            {name: 'index', url: partialPath + 'index.html', headerType: 'index'},
            {name: 'users', url: partialPath + 'user.html'},
            {name: 'comments', url: partialPath + 'comment.html'}
        ];

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
