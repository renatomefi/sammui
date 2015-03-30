'use strict';

angular.module('sammui.protocolServices', ['ngResource'])
    .service('protocolData', ['formProtocolManage', function (formProtocolManage) {
        var originalData = {};
        var currentData = {};

        this.getData = function (protocolId) {
            if (!currentData[protocolId]) {
                currentData[protocolId] = formProtocolManage.get({protocolId: protocolId});
                originalData[protocolId] = formProtocolManage.get({protocolId: protocolId});
            }

            return currentData[protocolId];
        };

        this.reloadOriginalData = function (protocolId) {
            originalData[protocolId] = formProtocolManage.get({protocolId: protocolId});
        };

        this.replaceDataByOriginal = function (protocolId, reload) {
            reload = reload || false;

            if (reload === true) {
                this.reloadOriginalData(protocolId);
            }

            currentData[protocolId] = angular.copy(originalData[protocolId]);
        };
    }])
    .factory('formProtocolManage', function ($resource) {
        return $resource('/form/protocol/:protocolId', {protocolId: '@protocolId'}, {
            'get': {
                cache: false
            }
        });
    })
    .factory('formProtocolUser', function ($resource) {
        return $resource('/form/protocol/:action/:protocolId/users/:userName',
            {
                action: '@action',
                protocolId: '@protocolId',
                userName: '@userName'
            }, {
                'add': {
                    method: 'PATCH',
                    params: {action: 'adds'}
                },
                'remove': {
                    method: 'PATCH',
                    params: {action: 'removes'}
                }
            });
    })
    .factory('formProtocolComment', function ($resource) {
        return $resource('/form/protocol/:action/:protocolId/:var/:commentId',
            {
                action: '@action',
                protocolId: '@protocolId',
                commentId: '@commentId',
                var: '@var'
            }, {
                'add': {
                    method: 'PATCH',
                    isArray: true,
                    params: {action: 'adds', var: 'comment'}
                },
                'remove': {
                    method: 'PATCH',
                    isArray: true,
                    params: {action: 'removes', var: 'comments'}
                }
            });
    })
    .factory('formProtocolConclusion', function ($resource) {
        return $resource('/form/protocol/conclusions/:protocolId',
            {
                protocolId: '@protocolId'
            }, {
                'save': {
                    method: 'PATCH'
                }
            });
    })
    .factory('formProtocol', function ($resource) {
        return $resource('/form/protocol/:formId', {formId: '@formId'}, {
            'generate': {
                method: 'POST'
            }
        });
    })
    .factory('formProtocols', function ($resource) {
        return $resource('/form/protocol/forms/:formId', {formId: '@id'}, {
            'query': {
                method: 'GET',
                isArray: false
            }
        });
    });