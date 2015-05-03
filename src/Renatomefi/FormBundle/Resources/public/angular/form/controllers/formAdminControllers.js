'use strict';

angular.module('sammui.formAdminControllers', ['ngRoute'])
    .controller('formAdminProtocols', ['$rootScope', '$scope', '$location', 'formList', 'formProtocol',
        function ($rootScope, $scope, $location, formList, formProtocol) {
            console.log('ok form admin');
        }
    ])
;
