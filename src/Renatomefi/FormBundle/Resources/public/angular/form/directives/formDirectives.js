'use strict';

angular.module('sammui.formDirectives', [])
    .directive('formFillingHeader', function () {
        return {
            restrict: 'E',
            templateUrl: '/bundles/form/angular/views/form/filling/partials/header.html'
        };
    });
