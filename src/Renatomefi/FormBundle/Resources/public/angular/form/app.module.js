'use strict';

var sammuiForm = angular.module('sammui.form', [
    'sammui.formServices',
    'sammui.formControllers',
    'sammui.protocolServices',
    'sammui.protocolControllers',
    'sammui.formDirectives'
]);

sammuiForm.config(function ($locationProvider, $routeProvider) {
    $routeProvider.when('/form', {
        templateUrl: '/bundles/form/angular/views/form/start.html',
        templatePreload: true,
        controller: 'formStart',
        reloadOnSearch: false
    });
    $routeProvider.when('/form/:protocolId', {
        templateUrl: '/bundles/form/angular/views/form/filling/home.html',
        templatePreload: false,
        controller: 'formFilling',
        reloadOnSearch: false
    });
    $routeProvider.when('/form/:protocolId/page/:pageId', {
        templateUrl: '/bundles/form/angular/views/form/filling/home.html',
        templatePreload: false,
        controller: 'formFilling',
        reloadOnSearch: false
    });
});