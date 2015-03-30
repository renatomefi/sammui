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
    ]);
