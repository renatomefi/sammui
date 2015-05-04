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
