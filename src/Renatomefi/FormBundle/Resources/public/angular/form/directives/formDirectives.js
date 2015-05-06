'use strict';

angular.module('sammui.formDirectives', [])
    .directive('formFillingHeader', function () {
        return {
            require: '^formFillingPage',
            restrict: 'E',
            replace: true,
            scope: {
                headerType: "@",
                templates: '=',
                protocol: '=',
                pages: '=',
                toPage: '&',
                currentPage: '='
            },
            templateUrl: '/bundles/form/angular/views/form/filling/partials/header.html'
        };
    })
    .directive('formField', function () {
        return {
            restrict: 'E',
            transclude: true,
            scope: true,
            link: function (scope, elem, attrs) {
                scope.fieldName = attrs.name;
            },
            templateUrl: '/bundles/form/angular/views/form/pages/field.html'
        };
    })
    .directive('selectField', function () {
        return {
            restrict: 'EA',
            scope: {
                model: '=ngModel',
                changeExpr: '@ngChange',
                options: '=options'
            },
            template: '' +
            '<select class="form-control" ng-model="model" ng-options="key as value for (key , value) in options">' +
            '<option></option>' +
            '</select>'
        };
    })
    .directive('selectMultiField', function () {
        return {
            restrict: 'EA',
            scope: {
                model: '=ngModel',
                options: '=options',
                prefix: '@'
            },
            link: function (scope) {
                if (!scope.prefix) {
                    scope.prefix = scope.$id;
                }
            },
            template: '' +
            '<div class="checkbox checkbox-primary" ng-repeat="(key, value) in options">' +
            '<input type="checkbox" id="{{::prefix}}_{{::key}}" ng-model="$parent.model[key]">' +
            '<label for="{{::prefix}}_{{::key}}">{{value}}</label>' +
            '</div>'
        };
    })
    .directive('booleanField', function () {
        return {
            restrict: 'E',
            scope: {
                model: '=ngModel'
            },
            controller: ['$scope', function ($scope) {
                $scope.btnClick = function (value) {
                    $scope.model = ($scope.model === value) ? null : value;
                };
            }],
            template: '' +
            '<div class="btn-group">' +
            '<button ng-click="btnClick(true)" ng-class="{active: model === true}" type="button" class="btn btn-primary">' +
            '<i class="glyphicon glyphicon-ok-sign"></i> ' + '{{\'form-value-true\' | translate}}' +
            '</button>' +
            '<button ng-click="btnClick(false)" ng-class="{active: model === false}" type="button" class="btn btn-primary">' +
            '<i class="glyphicon glyphicon-remove-sign"></i> ' + '{{\'form-value-false\' | translate}}' +
            '</button>' +
            '</div>'
        };
    })
    .directive('focusOn', function () {
        return {
            restrict: 'A',
            link: function (scope, elem, attrs) {
                scope.$on(attrs.focusOn, function () {
                    elem[0].focus();
                });
            }
        };
    })
;
