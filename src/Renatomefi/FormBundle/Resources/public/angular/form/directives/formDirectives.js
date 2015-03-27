'use strict';

angular.module('sammui.formDirectives', [])
    .directive('formFillingHeader', function () {
        return {
            restrict: 'E',
            replace: true,
            scope: {
                headerType: "@",
                templates: '=',
                protocol: '=',
                toPage: '&',
                currentPage: '='
            },
            templateUrl: '/bundles/form/angular/views/form/filling/partials/header.html'
        };
    });
