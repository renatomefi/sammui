'use strict';

angular.module('sammui.protocolControllers', ['ngRoute'])
    .controller('formFillingMain', ['$rootScope', '$scope', '$route', '$routeParams', '$location', 'formProtocolManage', 'protocolData',
        function ($rootScope, $scope, $route, $routeParams, $location, formProtocolManage, protocolData) {

            $rootScope.loading = true;

            $scope.currentTemplate = undefined;

            $scope.protocol = {
                data: protocolData.getData($routeParams.protocolId),
                original: protocolData.getOriginalData($routeParams.protocolId)
            };

            $scope.protocol.data.$promise.then(function () {
                $rootScope.loading = false;
            });

            $scope.protocol.data.$promise.catch(function () {
                $location.path('/form');
            });

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

        $scope.showThumbs = false;

        //TODO configuration file??
        var uploadPath = '/form/protocol/files/upload';
        var downloadPath = '/form/protocol/files/get/';

        $scope.$watch('files', function () {
            $scope.upload($scope.files);
        });

        $scope.showImage = function (image) {
            $scope.modal.data = image;
            $scope.Ui.turnOn('modalImage');
        };

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
    .controller('formFillingPagination', ['$scope', '$routeParams', '$location', '$route', 'formConfig',
        function ($scope, $routeParams, $location, $route, formConfig) {

            var partialPath = formConfig.template.partialPath;
            var templatePath = formConfig.template.pagesPath;

            $scope.modal = {
                data: undefined
            };

            //Default templates to all forms
            $scope.templates = [
                {name: 'index', url: partialPath + 'index.html', headerType: 'index'},
                {name: 'users', url: partialPath + 'user.html'},
                {name: 'comments', url: partialPath + 'comment.html'},
                {name: 'conclusion', url: partialPath + 'conclusion.html'},
                {name: 'upload', url: partialPath + 'upload.html'}
            ];

            $scope.toPage = function (pageId) {
                if (!angular.isUndefined($routeParams.pageId)) {
                    $route.updateParams({pageId: pageId});
                } else {
                    $location.path($location.path() + '/page/' + pageId);
                }
            };

            $scope.loadTemplate = function (pageId) {
                if (isFinite(parseInt(pageId))) {
                    $scope.currentPage = $scope.$parent.protocol.data.form.pages.filter(function (page) {
                        return page.number === parseInt(pageId);
                    }).pop();

                    $scope.$parent.currentTemplate = {
                        name: pageId,
                        url: templatePath + 'base.html',
                        headerType: 'form'
                    };
                } else {
                    $scope.selectedTemplate = $scope.$parent.currentTemplate = $scope.templates.filter(function (template) {
                        return pageId === template.name;
                    }).pop();
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

            $scope.$parent.protocol.data.$promise.then(function () {
                $scope.formPages = $scope.$parent.protocol.data.form.pages;
                $scope.onLoad();
            });

        }])
    .controller('formFillingPage', ['$scope', 'formProtocolFields', function ($scope, formProtocolFields) {
        $scope.$parent.protocol.data.$promise.then(function () {
            $scope.formFields = $scope.$parent.protocol.data.form.fields;
        });

        $scope.isValueUpdated = function (fieldValue, field) {
            if (!fieldValue.hasOwnProperty('value') && field.value === null) {
                return false;
            } else if (angular.equals(field.value, fieldValue.value)) {
                return false;
            } else if (field.value === fieldValue.value) {
                return false;
            }

            return true;
        };

        $scope.saveFields = function () {
            $scope.savingForm = true;

            var fieldsToSend = $scope.$parent.protocol.data.form.fields.filter(function (field) {
                var fieldValue = null;

                var fieldValues = $scope.$parent.protocol.data.field_values;
                for (var i = 0; i < fieldValues.length; i++) {
                    if (field.id === fieldValues[i].field.id) {
                        fieldValue = fieldValues[i];
                        break;
                    }
                }

                if (!fieldValue && (field.value !== null && field.value !== undefined)) {
                    return true;
                }

                if (!fieldValue && !field.value) {
                    return false;
                }

                return field.value !== fieldValue.value;
            });

            formProtocolFields
                .save({
                    protocolId: $scope.$parent.protocol.data.id,
                    data: fieldsToSend
                }, function (data) {
                    //$scope.$parent.protocol.data.form.fields = angular.copy(data.form.fields);
                    $scope.$parent.protocol.data.field_values = angular.copy(data.field_values);
                    $scope.$broadcast('event:form-fieldSaved');
                })
                .$promise.finally(function () {
                    $scope.savingForm = false;
                });
        };
    }])
    .controller('formFillingPageField', ['$scope', function ($scope) {
        // $scope.field is determined at ng-init for those who uses this controller
        $scope.field = {};
        $scope.fieldValue = {};

        var findFieldValueByField = function () {
            var fieldValues = $scope.$parent.protocol.data.field_values;

            var hashMap = $scope.$parent.protocol.data.field_values_hashmap_field;

            $scope.fieldValue = fieldValues[hashMap[$scope.field.id]] || {};
        };

        $scope.$on('event:form-fieldSaved', function () {
            findFieldValueByField();
        });

        var fieldNameWatch = $scope.$watch('fieldName', function () {
            $scope.field = $scope.$parent.protocol.data.form.fields.filter(function (value) {
                return ($scope.fieldName === value.name || $scope.fieldName === value.id);
            }).pop();

            fieldNameWatch();
        });

        var fieldWatchUnbind = $scope.$watch('field', function () {

            findFieldValueByField();

            // Check if field.value is null, if it is get the value from the protocol.data.form.values.{sameField}
            // this way we can setup the form from the server data
            if (!$scope.field.value) {
                $scope.field.value =
                    ($scope.fieldValue && $scope.fieldValue.hasOwnProperty('value')) ? angular.copy($scope.fieldValue.value) : null;
            }

            // Since we are not going to change the field, let's unbind it, you know, angular issues!
            fieldWatchUnbind();
        });

    }])
;
