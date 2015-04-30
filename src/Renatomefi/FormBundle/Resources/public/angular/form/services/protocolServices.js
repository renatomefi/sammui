'use strict';

angular.module('sammui.protocolServices', ['ngResource'])
    .service('protocolData', ['localStorageService', 'formProtocolManage', function (localStorageService, formProtocolManage) {
        var originalData = {};
        var currentData = {};

        var storagePrefix = 'protocol.';

        var updateStorage = function (protocolId, changes) {
            localStorageService.set(storagePrefix + protocolId, currentData[protocolId]);
            console.debug('Local storage for "' + protocolId + '" has been updated with changes', changes);
        };

        this.getData = function (protocolId) {

            if (!currentData[protocolId]) {

                currentData[protocolId] = formProtocolManage.get({protocolId: protocolId});

                currentData[protocolId].$promise.then(function (data) {
                    originalData[protocolId] = angular.copy(data);

                    localStorageService.set(storagePrefix + protocolId, currentData[protocolId]);

                    Object.observe(currentData[protocolId], function (changes) {
                        updateStorage(protocolId, changes);
                    });

                    currentData[protocolId].form.fields.map(function (item) {
                        Object.observe(item, function (changes) {
                            updateStorage(protocolId, changes);
                        });
                    });
                });
            }

            return currentData[protocolId];
        };

        this.getOriginalData = function (protocolId) {
            if (!currentData[protocolId]) {
                this.getData(protocolId);
            }

            return originalData[protocolId];
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
    .factory('formProtocolFields', function ($resource) {
        return $resource('/form/protocol/fields/:protocolId/save',
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