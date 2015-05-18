'use strict';

angular.module('sammui.protocolControllers', ['ngRoute'])
    .controller('formFillingMain', ['$rootScope', '$scope', '$route', '$routeParams', '$location', 'formProtocolManage', 'protocolData', 'formProtocolLock',
        function ($rootScope, $scope, $route, $routeParams, $location, formProtocolManage, protocolData, formProtocolLock) {

            $rootScope.loading = true;

            $scope.currentTemplate = undefined;

            protocolData.setScope($scope);

            $scope.protocol = {
                data: protocolData.getData($routeParams.protocolId),
                original: protocolData.getOriginalData($routeParams.protocolId),
                readOnly: true,
                published: false
            };

            $scope.protocol.data.$promise.then(function () {
                $scope.isReadOnly = function () {
                    var publish = $scope.protocol.data.publish;
                    if (publish.length > 0 && publish[0].locked === true) {
                        $scope.protocol.published = true;
                        return true;
                    }
                    return !!($location.search().hasOwnProperty('readOnly'));
                };

                $scope.protocol.readOnly = $scope.isReadOnly();

                $rootScope.loading = false;
            });

            $scope.protocol.data.$promise.catch(function () {
                $location.path('/form');
            });

            $scope.publishProtocol = function () {
                formProtocolLock
                    .lock(
                    {protocolId: $scope.protocol.data.id})
                    .$promise.then(function (data) {
                        if (data.isLocked === true) {
                            $rootScope.modalInfoBody = 'form-protocol-publish-success';
                            $location.path('/form');
                            $rootScope.Ui.turnOn("modalInfo");
                        }
                    });
            };

            $scope.toggleReadOnly = function () {
                if (false === $scope.protocol.readOnly) {
                    $location.search('readOnly');
                } else {
                    $location.search('readOnly', null);
                }
            };

            $scope.$on('$locationChangeSuccess', function () {
                $scope.protocol.data.$promise.then(function () {
                    $scope.protocol.readOnly = $scope.isReadOnly();
                });
            });

        }
    ])
    .controller('formFillingPagination', ['$scope', '$routeParams', '$location', '$route', 'formConfig', 'formActionsTemplates',
        function ($scope, $routeParams, $location, $route, formConfig, formActionsTemplates) {

            var templatePath = formConfig.template.pagesPath;

            $scope.modal = {
                data: undefined
            };

            $scope.templates = formActionsTemplates.get();

            $scope.toPage = function (pageId) {
                if (!angular.isUndefined($routeParams.pageId)) {
                    $route.updateParams({pageId: pageId});
                } else {
                    $location.path($location.path() + '/page/' + pageId);
                }
            };

            $scope.loadTemplate = function (pageId) {
                if (isFinite(parseInt(pageId, 10))) {
                    $scope.currentPage = $scope.$parent.protocol.data.form.pages.filter(function (page) {
                        return page.number === parseInt(pageId, 10);
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

        $scope.showFieldDetails = false;

        $scope.toggleShowFieldDetails = function () {
            $scope.showFieldDetails = !$scope.showFieldDetails;
        };

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
                    protocolId: $scope.protocol.data.id,
                    data: fieldsToSend,
                    group: $scope.protocol.data.current_group
                }, function (data) {
                    $scope.$parent.protocol.data.field_values = angular.copy(data.field_values);
                    $scope.$on('event:protocol-field_values-updated', function () {
                        $scope.$broadcast('event:form-fieldSaved');
                    });
                })
                .$promise.finally(function () {
                    $scope.savingForm = false;
                });
        };

    }])
    .controller('formFillingPageField', ['$scope', function ($scope) {

        $scope.controllerReady = false;

        // $scope.field is determined at ng-init for those who uses this controller
        $scope.field = {};
        $scope.fieldValue = {};

        $scope.freeTextEnabled = false;

        $scope.clearCurrentValue = function () {
            $scope.field.value = null;
        };

        $scope.$watch('field.value', function () {
            if ($scope.field.hasOwnProperty('free_text_option')) {
                var freeTextOption = $scope.field.free_text_option;

                var isFreeTextSelected = ($scope.field.value === freeTextOption);
                if (isFreeTextSelected ||
                    (!$scope.field.options.hasOwnProperty($scope.field.value) && $scope.field.value !== null)) {
                    if (isFreeTextSelected) {
                        $scope.field.value = '';
                    }
                    $scope.freeTextEnabled = true;
                    $scope.$broadcast('event:form-fieldFreeTextEnabled');
                } else {
                    $scope.freeTextEnabled = false;
                }
            }
        });

        $scope.checkDependenciesSatisfied = function () {
            if ($scope.controllerReady === false) {
                return true;
            }

            if (!$scope.field.hasOwnProperty('depends_on') || $scope.field.depends_on.length === 0) {
                return true;
            }

            var fieldHashMap = $scope.$parent.protocol.data.form.fields_hashmap_name;

            // true means that this field will not open, since the dependencies were not resolved, or they were unmet!
            var unmet = (function () {
                //console.log('Current field is: ' + $scope.field.name);
                for (var i = 0; i < $scope.field.depends_on.length; i++) {
                    var dependency = $scope.field.depends_on[i];
                    var field = $scope.$parent.protocol.data.form.fields[fieldHashMap[dependency.field.name]];

                    //console.log('Dependency "' + field.name + '", current value: ' + JSON.stringify(field.value));

                    if (angular.isUndefined(dependency.custom_value) || dependency.custom_value.length === 0) {
                        if (!field.value || field.value === false) {
                            return true;
                        }
                    } else {
                        // Prove that you have the correct value :)
                        // This is an OR condition, if any value from custom_value matches, the return will happens
                        for (var j = 0; j < dependency.custom_value.length; j++) {
                            var cValue = dependency.custom_value[j];

                            if (cValue === field.value) {
                                return false;
                            }

                            if (field.hasOwnProperty('options') && Object.keys(field.options).length > 0) {
                                if (field.options[cValue] &&
                                    field.value !== null && field.value !== undefined &&
                                    Object.getOwnPropertyNames(field.value).length > 0 &&
                                    field.value[cValue] === true) {
                                    return false;
                                }
                            }
                        }

                        return true;
                    }
                }
            })();

            if (unmet === true) {
                $scope.$broadcast('event:form-fieldUnmetDependencies');
            }

            return !unmet;
        };

        var findFieldValueByField = function () {
            var fieldValues = $scope.$parent.protocol.data.field_values;
            var hashMap = $scope.$parent.protocol.data.field_values_hashmap_field;

            $scope.fieldValue = fieldValues[hashMap[$scope.field.id]] || {};
        };

        // Find the field_values every time the form is saved
        $scope.$on('event:form-fieldSaved', function () {
            findFieldValueByField();
        });

        // If the field have unmet dependencies just clear it value
        $scope.$on('event:form-fieldUnmetDependencies', function () {
            $scope.field.value = null;
        });

        // When the form-field directive is initialized it has a fieldName, this function will bind it with the field
        var fieldNameWatch = $scope.$watch('fieldName', function () {
            var hashMap = $scope.$parent.protocol.data.form.fields_hashmap_name;

            $scope.field = $scope.$parent.protocol.data.form.fields[hashMap[$scope.fieldName]];

            fieldNameWatch();
        });

        // Initialize the field and set the best value
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

            $scope.controllerReady = true;
        });

    }])
    .controller('formFillingUser', ['$rootScope', '$scope', 'formProtocolUser', function ($rootScope, $scope, formProtocolUser) {

        $scope.newUser = undefined;
        $scope.loading = false;

        $scope.addUser = function (userName) {
            $scope.loading = true;

            formProtocolUser
                .add({
                    protocolId: $scope.protocol.data.id,
                    userName: userName
                }, function (data) {
                    $scope.newUser = null;
                    $scope.protocol.data.user = angular.copy(data.user);
                    $scope.protocol.data.non_user = angular.copy(data.nonUser);
                })
                .$promise.finally(function () {
                    $scope.loading = false;
                });
        };

        $scope.removeUser = function (userName) {
            $scope.loading = true;

            formProtocolUser
                .remove({
                    protocolId: $scope.protocol.data.id,
                    userName: userName
                }, function (data) {
                    $scope.protocol.data.user = angular.copy(data.user);
                    $scope.protocol.data.non_user = angular.copy(data.nonUser);
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
                    protocolId: $scope.protocol.data.id,
                    body: $scope.newComment
                }, function (data) {
                    $scope.newComment = null;
                    $scope.protocol.data.comment = angular.copy(data);
                })
                .$promise.finally(function () {
                    $scope.loading = false;
                });
        };

        $scope.removeComment = function (commentId) {
            $scope.loading = true;

            formProtocolComment
                .remove({
                    protocolId: $scope.protocol.data.id,
                    commentId: commentId
                }, function (data) {
                    $scope.protocol.data.comment = angular.copy(data);
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
        var uploadPath = '/form/protocol/files/upload',
            downloadPath = '/form/protocol/files/get/';

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
;
