'use strict';

var sammuiForm = angular.module('sammui.form', [
    'sammui.formServices',
    'sammui.formControllers',
    'sammui.formAdminControllers',
    'sammui.formDirectives',
    'sammui.protocolServices',
    'sammui.protocolControllers'
]);

sammuiForm.config(function ($locationProvider, $routeProvider) {
    $routeProvider.when('/form', {
        templateUrl: '/bundles/form/angular/views/form/start.html',
        templatePreload: true,
        controller: 'formStart',
        reloadOnSearch: false
    });
    $routeProvider.when('/form/:protocolId/:page?/:pageId?', {
        templateUrl: '/bundles/form/angular/views/form/filling/home.html',
        templatePreload: true,
        controller: 'formFillingMain',
        reloadOnSearch: false
    });
    // Form admin
    $routeProvider.when('/admin/form', {
        templateUrl: '/bundles/form/angular/views/form/admin/start.html',
        templatePreload: false,
        controller: 'formAdminStart',
        reloadOnSearch: false
    });
    $routeProvider.when('/admin/form/:formId?/:protocolId?', {
        templateUrl: '/bundles/form/angular/views/form/admin/protocols.html',
        templatePreload: false,
        controller: 'formAdminProtocols',
        reloadOnSearch: false
    });
});

/*
 'form-value-null' => 'Nenhum valor'
 'form-value-true' => 'Verdadeiro'
 'form-value-false' => 'Falso'
 */
sammuiForm.filter('fieldHumanValue', function($filter) {
    return function(input) {
        var field = (input.hasOwnProperty('field')) ? input.field : input;

        if (field.value === null) {
            return $filter('translate')('form-value-null');
        }

        if (field.value === 'true' || field.value === true) {
            return $filter('translate')('form-value-true');
        }

        if (field.value === 'false' || field.value === false) {
            return $filter('translate')('form-value-false');
        }

        if (field.hasOwnProperty('options')) {
            if (field.options[field.value]) {
                return field.options[field.value];
            } else if (field.hasOwnProperty('free_text_option')) {
                return field.options[field.free_text_option] + ': ' + field.value;
            }
        }

        return field.value;
    };
});