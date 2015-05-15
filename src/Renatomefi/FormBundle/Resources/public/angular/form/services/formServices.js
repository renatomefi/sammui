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
                generateFormPageUrl: function (formTemplate, pageId) {
                    return this.pagesPath + formTemplate + '/' + pageId + '.html';
                },
                generatePageUrl: function (page) {
                    return this.pagesPath + page + '.html';
                },
                generatePartialUrl: function (page) {
                    return this.partialPath + page + '.html';
                }
            }
        };
    })
    .factory('formActionsTemplates', ['$templateCache', '$http', 'formConfig', function ($templateCache, $http, formConfig) {
        return {
            get: function () {
                return [
                    {name: 'index', url: formConfig.template.generatePartialUrl('index'), headerType: 'index'},
                    {name: 'users', url: formConfig.template.generatePartialUrl('user')},
                    {name: 'comments', url: formConfig.template.generatePartialUrl('comment')},
                    {name: 'conclusion', url: formConfig.template.generatePartialUrl('conclusion')},
                    {name: 'upload', url: formConfig.template.generatePartialUrl('upload')}
                ];
            },
            preload: function () {
                this.get().map(function (item) {
                    $http.get(item.url, {cache: $templateCache});
                });
            }
        };
    }])
    .factory('formPagesTemplate', ['$templateCache', '$http', 'formConfig', function ($templateCache, $http, formConfig) {
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
            preload: loadTemplates
        };
    }]);