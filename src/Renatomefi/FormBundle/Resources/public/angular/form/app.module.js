'use strict';

var sammuiForm = angular.module('sammui.form', [
    'sammui.formServices',
    'sammui.formControllers'
]);

sammuiApi.config(function ($locationProvider, $routeProvider) {
    $routeProvider.when('/form/start', {
        templateUrl: '/bundles/form/angular/views/form/start.html',
        templatePreload: true,
        controller: 'formStart',
        reloadOnSearch: false
    });
    $routeProvider.when('/form/start/:protocolId', {
        templateUrl: '/bundles/form/angular/views/form/filling.html',
        templatePreload: false,
        controller: 'formFilling',
        reloadOnSearch: true
    });
});