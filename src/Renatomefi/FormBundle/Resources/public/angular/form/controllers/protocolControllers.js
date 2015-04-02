'use strict';

angular.module('sammui.protocolControllers', ['ngRoute'])
    .controller('formFilling', ['$rootScope', '$scope', '$route', '$routeParams', '$location', 'formProtocolManage', 'protocolData',
        function ($rootScope, $scope, $route, $routeParams, $location, formProtocolManage, protocolData) {

            $scope.currentTemplate = undefined;

            $scope.protocol = {
                data: protocolData.getData($routeParams.protocolId),
                original: protocolData.getOriginalData($routeParams.protocolId)
            };

            //TODO Review this process, it should be more complete, probably check the promise when it's done and have a mongoId
            $scope.loadProtocol = function () {
                if (!$scope.protocol.data) {
                    $location.path('/form');
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
    }])
    .controller('formFillingConclusion', ['$scope', 'formProtocolConclusion', function ($scope, formProtocolConclusion) {
        $scope.loading = true;

        $scope.savedConclusion = undefined;
        $scope.currentConclusion = undefined;

        $scope.$parent.protocol.data.$promise.then(function () {
            $scope.loading = false;
            $scope.savedConclusion = angular.copy($scope.$parent.protocol.data.conclusion);
            $scope.currentConclusion = angular.copy($scope.$parent.protocol.data.conclusion);
        });

        $scope.saveConclusion = function () {
            $scope.loading = true;
            formProtocolConclusion
                .save({
                    protocolId: $scope.$parent.protocol.data.id,
                    conclusion: $scope.currentConclusion
                }, function () {
                    $scope.$parent.protocol.data.conclusion = angular.copy($scope.currentConclusion);
                    $scope.savedConclusion = angular.copy($scope.currentConclusion);
                })
                .$promise.finally(function () {
                    $scope.loading = false;
                });
        };

    }])
    .controller('formFillingUpload', ['$scope', '$upload', '$http', function ($scope, $upload, $http) {

        //TODO configuration file??
        var uploadPath = '/form/protocol/files/upload';
        var downloadPath = '/form/protocol/files/get/';

        $scope.$watch('files', function () {
            $scope.upload($scope.files);
        });

        $scope.downloadUrl = function (fileId) {
            return downloadPath + fileId;
        };

        $scope.deleteFile = function (fileId) {
            return $http.delete(
                '/form/protocol/files/delete/protocol/' +
                $scope.$parent.protocol.data.id +
                '/file/' +
                fileId).success(uploadSuccess);
        };

        $scope.updateFile = function (file, type) {
            var params = {};

            if (type === 'title') {
                params.title = file.title;
            }
            if (type === 'desc') {
                params.description = file.description;
            }

            return $http.patch(
                '/form/protocol/files/protocol/' +
                $scope.$parent.protocol.data.id +
                '/file/' +
                file.id, params);

        };

        var uploadProgress = function (evt) {
            evt.config.file.progress = parseInt(100.0 * evt.loaded / evt.total);
        };

        var uploadSuccess = function (data) {
            $scope.$parent.protocol.data.file = angular.copy(data);
        };

        var uploadError = function (data, status, headers, config) {
            config.file.error = true;
            config.file.progress = 100;
        };

        $scope.upload = function (files) {
            if (files && files.length) {
                angular.forEach(files, function (file) {
                    $upload
                        .upload({
                            url: uploadPath + '/' + $scope.$parent.protocol.data.id,
                            file: file
                        })
                        .progress(uploadProgress)
                        .success(uploadSuccess)
                        .error(uploadError);
                });
            }
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
            {name: 'upload', url: partialPath + 'upload.html'}
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
        };

        $scope.loadTemplate = function (pageId) {
            if (isFinite(parseInt(pageId))) {
                $scope.$parent.currentTemplate = {
                    name: pageId,
                    url: templatePath + pageId + '.html',
                    headerType: 'form'
                };
            } else {
                for (var i = 0, len = $scope.templates.length; i < len; i++) {
                    if (pageId === $scope.templates[i].name) {
                        $scope.selectedTemplate = $scope.$parent.currentTemplate = $scope.templates[i];
                        break;
                    }
                }
            }
        };

        $scope.onLoad = function () {
            var pageId = $routeParams.pageId;

            if (pageId === undefined) {
                $scope.toPage('index');
                return;
            }

            if ($scope.selectedTemplate && pageId === $scope.selectedTemplate.name) {
                return;
            }

            $scope.loadTemplate(pageId);
        };

        $scope.onLoad();

    }]);
