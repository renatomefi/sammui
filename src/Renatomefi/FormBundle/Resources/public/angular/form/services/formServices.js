'use strict';

angular.module('sammui.formServices', ['ngResource'])
    .factory('formList', function ($resource) {
        return $resource('/form/manage/list/all');
    })
    .factory('formManage', function ($resource) {
        return $resource('/form/manage/:formId', {formId: '@formId'});
    });