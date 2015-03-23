'use strict';

angular.module('sammui.formServices', ['ngResource'])
    .factory('formList', function ($resource) {
        return $resource('/form/manage/list/all');
    })
    .factory('formManage', function ($resource) {
        return $resource('/form/manage/:formId', {formId: '@formId'});
    })
    .factory('formProtocolManage', function ($resource) {
        return $resource('/form/protocol/:protocolId', {protocolId: '@protocolId'}, {
            'get': {
                cache: true
            }
        });
    })
    .factory('formProtocolUser', function ($resource) {
        return $resource('/form/protocol/:action/:protocolId/users/:userName',
            {
                action: '@action',
                protocolId: '@protocolId',
                userName: '@userName'
            },
            {
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
    .factory('formProtocol', function ($resource) {
        var r = $resource('/form/protocol/:formId', {formId: '@formId'}, {
            'generate': {
                method: 'POST'
            }
        });

        // Forcing remove unabled methods on FormProtocol service
        delete r.prototype.get;
        delete r.prototype.save;
        delete r.prototype.query;
        delete r.prototype.delete;
        delete r.prototype.remove;

        return r;
    })
    .factory('formProtocols', function ($resource) {
        return $resource('/form/protocol/forms/:formId', {formId: '@id'}, {
            'query': {
                method: 'GET',
                isArray: false
            }
        });
    });