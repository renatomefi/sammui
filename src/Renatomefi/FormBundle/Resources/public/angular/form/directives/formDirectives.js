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
            //require: ['^transclude','^formFillingPage'],
            restrict: 'E',
            transclude: true,
            scope: true,
            link: function (scope, element, attrs, ngModelCtrl, transclude) {
                scope.fieldName = attrs.name;
            },
            templateUrl: '/bundles/form/angular/views/form/pages/field.html'
        };
    })
    .directive('focusOn', function () {
        return function (scope, elem, attr) {
            scope.$on(attr.focusOn, function () {
                elem[0].focus();
            });
        };
    });
