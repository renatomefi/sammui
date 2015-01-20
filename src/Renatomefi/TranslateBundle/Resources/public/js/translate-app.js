'use strict'

// Configuring $translateProvider
angular.module('sammui').config(['$translateProvider', function ($translateProvider) {

    $translateProvider.preferredLanguage('en-us');
    $translateProvider.useLoader('translateLoader');

}]);