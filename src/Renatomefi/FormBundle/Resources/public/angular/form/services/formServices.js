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
    })
    .factory('formTemplate', ['$templateCache', '$http', 'formConfig', function ($templateCache, $http, formConfig) {
        var loadTemplates = function (form) {
            form.pages.map(function (item) {
                item.url = formConfig.template.generatePageUrl(form.template, item.number);
                $http.get(item.url, {cache: $templateCache});
            });
        };

        return {
            loadTemplates: loadTemplates
        };
    }]);