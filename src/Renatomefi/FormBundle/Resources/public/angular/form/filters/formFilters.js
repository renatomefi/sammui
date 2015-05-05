'use strict';

angular.module('sammui.formFilters', [])
    .filter('fieldHumanValue', function ($filter) {
        return function (input) {
            var field = (input.hasOwnProperty('field')) ? input.field : input;

            if (field.value === null) {
                return '<em>' + $filter('translate')('form-value-null') + '</em>';
            }

            if (field.value === 'true' || field.value === true) {
                return '<em>' + $filter('translate')('form-value-true') + '</em>';
            }

            if (field.value === 'false' || field.value === false) {
                return '<em>' + $filter('translate')('form-value-false') + '</em>';
            }

            if (field.hasOwnProperty('options')) {
                if (field.value instanceof Object) {
                    var text = '<ul>';
                    angular.forEach(field.value, function (value, key) {
                        if (value === true) {
                            text += '<li>' + field.options[key] + '</li>';
                        }
                    });
                    return text + '</ul>';
                } else {
                    if (field.options[field.value]) {
                        return field.options[field.value];
                    } else if (field.hasOwnProperty('free_text_option')) {
                        return field.options[field.free_text_option] + ': ' + field.value;
                    }
                }
            }

            return field.value;
        };
    });