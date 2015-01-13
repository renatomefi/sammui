'use strict'

// Configuring $translateProvider
angular.module('sammui').config(['$translateProvider', function ($translateProvider) {
    $translateProvider.preferredLanguage('en-us');

    // Simply register translation table as object hash
    $translateProvider.translations('en-us', {
        'index-title': 'SAMMUI Sample APP',
        'index-title-sidebar-left': 'Menu',
        'index-title-sidebar-right': 'admin'
    });

    $translateProvider.translations('pt-br', {
        'index-title': 'SAMMUI Exemplo',
        'index-title-sidebar-left': 'Menu',
        'index-title-sidebar-right': 'admin'
    });
}]);
