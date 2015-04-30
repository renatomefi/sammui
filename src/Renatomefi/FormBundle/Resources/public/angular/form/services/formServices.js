'use strict';

angular.module('sammui.formServices', ['ngResource'])
    .factory('formList', function ($resource) {
        return $resource('/form/manage/list/all');
    })
    .factory('formManage', function ($resource) {
        return $resource('/form/manage/:formId', {formId: '@formId'});
    })
    .service('formConfig', function () {
        return {
            template: {
                partialPath: '/bundles/form/angular/views/form/filling/partials/',
                pagesPath: '/bundles/form/angular/views/form/pages/',
                generatePageUrl: function (formTemplate, pageId) {
                    return this.pagesPath + formTemplate + '/' + pageId + '.html';
                }
            }
        };
    });