'use strict';

angular.module('sammui.formAdminControllers', ['ngRoute'])
    .controller('formAdmin', ['$scope', 'formList',
        function ($scope, formList) {

        }
    ])
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
    .controller('formAdminProtocols', ['$rootScope', '$scope', '$location', 'formList', 'formProtocol',
        function ($rootScope, $scope, $location, formList, formProtocol) {
            console.log('time to see protocols');
        }
    ])
;
