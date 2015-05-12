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
    .controller('formAdminProtocols', ['$rootScope', '$scope', '$location', '$routeParams', 'formProtocols', 'formManage', 'formProtocolManage',
        function ($rootScope, $scope, $location, $routeParams, formProtocols, formManage, formProtocolManage) {
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

            $scope.protocolDetailsModal = function (protocolId) {
                $rootScope.loading = true;
                formProtocolManage
                    .get({protocolId: protocolId})
                    .$promise.then(function (data) {
                        $scope.protocol = data;
                        $scope.protocol.isLocked = (function() {
                           if (data.publish.length > 0 && data.publish[0].locked === true) {
                               return true;
                           }
                        })();
                        $rootScope.loading = false;
                        $rootScope.Ui.turnOn('modalProtocolDetails');
                    });
            };
        }
    ])
;
