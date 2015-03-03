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
    .controller('formFilling', ['$rootScope', '$scope', '$location', 'formProtocolManage',
        function ($rootScope, $scope, $location, formProtocolManage) {

            $scope.protocol = {
                current: undefined,
                data: undefined
            }


        }
    ]);
