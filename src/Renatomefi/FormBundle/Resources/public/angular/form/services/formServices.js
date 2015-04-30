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
                partials: ['field', 'base'],
                //TODO bring templates from formFillingPagination $scope.templates
                generateFormPageUrl: function (formTemplate, pageId) {
                    return this.pagesPath + formTemplate + '/' + pageId + '.html';
                },
                generatePageUrl: function (page) {
                    return this.pagesPath + page + '.html';
                }
            }
        };
    })
    .factory('formTemplate', ['$templateCache', '$http', 'formConfig', function ($templateCache, $http, formConfig) {
        var loadTemplates = function (form) {
            form.pages.map(function (item) {
                item.url = formConfig.template.generateFormPageUrl(form.template, item.number);
                $http.get(item.url, {cache: $templateCache});
            });
            formConfig.template.partials.map(function (page) {
                var url = formConfig.template.generatePageUrl(page);
                $http.get(url, {cache: $templateCache});
            });
        };

        return {
            loadTemplates: loadTemplates
        };
    }]);