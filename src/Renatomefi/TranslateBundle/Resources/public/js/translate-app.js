'use strict'

// Configuring $translateProvider
angular.module('sammui').config(['$translateProvider', function ($translateProvider) {

    console.debug('Loading Translations');

    $translateProvider.preferredLanguage('en-us');
    $translateProvider.useLoader('translateLoader');

}]);
