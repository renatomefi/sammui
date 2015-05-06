'use strict';

/* Directives */
angular.module('sammui.mainDirectives', [])
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