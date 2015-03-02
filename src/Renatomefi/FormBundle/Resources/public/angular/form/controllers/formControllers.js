'use strict';

angular.module('sammui.formControllers', ['ngRoute'])
    .controller('formStart', ['$rootScope', '$scope', 'formList',
        function ($rootScope, $scope, formList) {

            $scope.forms = {
                current: undefined,
                loading: undefined,
                data: undefined
            };

            $scope.loadForms = function () {
                $scope.forms.loading = true;
                formList.query({}, function(data) {
                    $scope.forms.data = data;
                }).$promise.finally(function () {
                    $scope.forms.loading = false;
                });
            };

            $scope.loadForms();
        }
    ]);
