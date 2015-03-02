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
});

sammuiForm.run(function(formProtocol){
    var protocol = formProtocol.generate({formId: '54f49df7eabc883f048b456a'});
    console.debug('Generated Protocol', protocol);
});