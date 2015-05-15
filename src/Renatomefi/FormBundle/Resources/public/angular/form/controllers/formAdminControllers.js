'use strict';

angular.module('sammui.formAdminControllers', ['ngRoute'])
    .controller('formAdminStart', ['$scope', '$location', 'formList',
        function ($scope, $location, formList) {

            $scope.forms = {
                current: undefined,
                loading: undefined,
                data: undefined
            };

            $scope.loadForms = function (reload) {
                reload = reload || false;

                if (!$scope.forms.data && reload === false) {
                    $scope.forms.loading = true;
                    formList.query({},
                        function (data) {
                            $scope.forms.data = data;
                        }).$promise.finally(function () {
                            $scope.forms.loading = false;
                        });
                }
            };

            $scope.startForm = function (formId) {
                $location.path('/admin/form/' + formId);
            };

            $scope.loadForms();

        }
    ])
    .controller('formAdminProtocols', ['$rootScope', '$scope', '$location', '$translate', '$routeParams', 'formProtocols', 'formManage', 'formProtocolManage', 'formProtocolLock',
        function ($rootScope, $scope, $location, $translate, $routeParams, formProtocols, formManage, formProtocolManage, formProtocolLock) {
            $rootScope.loading = true;

            // Loading form
            formManage.get(
                {formId: $routeParams.formId}, function (data) {
                    $scope.form = data;
                });

            // Loading protocols from form
            formProtocols.query(
                {formId: $routeParams.formId}, function (data) {
                    $scope.protocols = data;
                }).$promise.finally(function () {
                    $rootScope.loading = false;
                });

            $scope.readProtocol = function (protocolId) {
                return '#/form/' + protocolId + '/page/index?readOnly';
            };

            $scope.protocolExportUrl = function (handler, protocolId) {
                protocolId = protocolId || $scope.protocol.id;
                return '/form/protocol/export/' + handler +'/' + protocolId + '/' + $translate.use();
            };

            $scope.protocolDetailsModal = function (protocolId) {
                $rootScope.loading = true;
                formProtocolManage
                    .get({protocolId: protocolId})
                    .$promise.then(function (data) {
                        $scope.protocol = data;
                        $scope.protocol.isLocked = (function () {
                            if (data.publish.length > 0 && data.publish[0].locked === true) {
                                return true;
                            }
                        })();
                        $rootScope.loading = false;
                        $rootScope.Ui.turnOn('modalProtocolDetails');
                    });
            };

            $scope.lockProtocol = function (lock) {
                var lockProcess = function (protocolId, promise) {
                    $rootScope.loading = true;
                    $rootScope.Ui.turnOff('modalProtocolDetails');
                    promise.then(function () {
                        $scope.protocolDetailsModal(protocolId);
                    });
                };
                if (lock === true) {
                    lockProcess($scope.protocol.id, formProtocolLock.lock(
                        {protocolId: $scope.protocol.id}
                    ).$promise);
                }
                if (lock === false) {
                    lockProcess($scope.protocol.id, formProtocolLock.unlock(
                        {protocolId: $scope.protocol.id}
                    ).$promise);
                }
            };
        }
    ])
;
