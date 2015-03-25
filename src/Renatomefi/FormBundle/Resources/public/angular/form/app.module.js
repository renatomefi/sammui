'use strict';

var sammuiForm = angular.module('sammui.form', [
    'sammui.formServices',
    'sammui.formControllers'
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
        //templateUrl: function (parameters) {
        //    return '/bundles/form/angular/views/form/pages/sammui_demo/' + parameters.pageId + '.html';
        //},
        templateUrl: '/bundles/form/angular/views/form/filling/home.html',
        templatePreload: false,
        controller: 'formFilling',
        reloadOnSearch: false
    });
});

sammuiForm.directive('formFillingHeader', function() {
    return {
        templateUrl: '/bundles/form/angular/views/form/filling/partials/header.html'
    };
});