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
            template: '<select class="form-control" ng-model="model" ng-options="key as value for (key , value) in options"><option></option></select>'
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
    .directive('mefiTransclude', function () {
        /**
         * @returns {string} Returns the string representation of the element.
         */
        function startingTag(element) {
            element = angular.element(element).clone();
            try {
                // turns out IE does not let you set .html() on elements which
                // are not allowed to have children. So we just ignore it.
                element.empty();
            } catch (e) {
            }
            var elemHtml = angular.element('<div>').append(element).html();
            try {
                return element[0].nodeType === 3 ? angular.lowercase(elemHtml) :
                    elemHtml.
                        match(/^(<[^>]+>)/)[1].
                        replace(/^<([\w\-]+)/, function (match, nodeName) {
                            return '<' + angular.lowercase(nodeName);
                        });
            } catch (e) {
                return angular.lowercase(elemHtml);
            }

        }

        return {
            restrict: 'EA',
            link: function ($scope, $element, $attrs, controller, $transclude) {
                if (!$transclude) {
                    throw minErr('ngTransclude')('orphan',
                        'Illegal use of ngTransclude directive in the template! ' +
                        'No parent directive that requires a transclusion found. ' +
                        'Element: {0}',
                        startingTag($element));
                }

                $transclude($scope, function (clone) {
                    $element.empty();
                    $element.append(clone);
                });
            }
        };
    })
;
