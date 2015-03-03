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
    .controller('formFilling', ['$rootScope', '$scope', '$route', '$routeParams', '$location', 'formProtocolManage',
        function ($rootScope, $scope, $route, $routeParams, $location, formProtocolManage) {

            $scope.protocol = {
                data: undefined,
                currentPage: $routeParams.pageId
            };

            //$scope.userType = 'guest';

            $scope.loadProtocol = function () {
                $rootScope.loading = true;

                formProtocolManage.get(
                    {protocolId: $routeParams.protocolId},
                    function (data) {
                        $scope.protocol.data = data;
                    },
                    function () {
                        $location.path('/form/start');
                    })
                    .$promise.finally(function () {
                        $rootScope.loading = false;
                    });
            };

            $scope.toPage = function (pageId) {
                if (!angular.isUndefined($routeParams.pageId)) {
                    $route.updateParams({pageId: pageId});
                } else {
                    $location.path($location.path() + '/page/' + pageId);
                }
            };

            $scope.loadProtocol();

        }
    ]);
