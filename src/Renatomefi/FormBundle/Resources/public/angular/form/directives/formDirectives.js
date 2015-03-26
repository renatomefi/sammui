'use strict';

angular.module('sammui.formDirectives', [])
    .directive('formFillingHeader', function () {
        return {
            restrict: 'E',
            scope: {
                headerType: "@type",
                protocol: '=protocol'
            },
            templateUrl: '/bundles/form/angular/views/form/filling/partials/header.html'
        };
    });
