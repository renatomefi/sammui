'use strict';

/* Controllers */

angular.module('sammui.controllers', ['ngCookies'])

    .controller('Login', ['$rootScope', '$scope', '$window', 'Salt', 'Digest', function ($rootScope, $scope, $window, Salt, Digest) {
        // On Submit function
        $scope.getSalt = function () {

            //if (typeof $scope.user == 'undefined') return;
            //
            //$scope.user = {
            //    username : null,
            //    password: null
            //};
            var username = $scope.user.username;
            var password = $scope.user.password;
            // Get Salt
            Salt.get({username: username}, function (data) {
                var salt = data.salt;
                // Encrypt password accordingly to generate secret
                Digest.plain(password, salt).then(function (secret) {
                    // Display salt and secret for this example
                    $scope.user.salt = salt;
                    $scope.user.secret = secret;
                    // Store auth informations in rootScope for multi views access
                    $rootScope.userAuth = {username: $scope.user.username, secret: $scope.user.secret};
                }, function (err) {
                    console.log(err);
                });
            });
        };
    }])
    .controller('UiCtrl', ['$rootScope', '$scope', function ($rootScope, $scope) {

        // User agent displayed in home page
        $scope.userAgent = navigator.userAgent;

    }])
    .controller('MyCtrl1', ['$rootScope', '$scope', '$window', 'Hello', 'Salt', function ($rootScope, $scope, $window, Hello, Salt) {
        // If not authenticated, go to login

        if (typeof $rootScope.userAuth == "undefined") {
            $window.location = '#/login';
            return;
        }

        // Simple communication sample, return world
        $scope.hello = Hello.get({username: $rootScope.userAuth.username, secret: $rootScope.userAuth.secret});
    }])
    .controller('MyCtrl2', ['$rootScope', '$scope', '$window', 'Todos', function ($rootScope, $scope, $window, Todos) {
        // If not authenticated, go to login
        if (typeof $rootScope.userAuth == "undefined") {
            $window.location = '#/login';
            return;
        }

        // Load Todos with secured connection
        $scope.todos = Todos.query({username: $rootScope.userAuth.username, secret: $rootScope.userAuth.secret});

        $scope.addTodo = function () {
            var todo = {text: $scope.todoText, done: false};
            $scope.todos.push(todo);
            $scope.todoText = '';
        };

        $scope.remaining = function () {
            var count = 0;
            angular.forEach($scope.todos, function (todo) {
                count += todo.done ? 0 : 1;
            });
            return count;
        };

        $scope.archive = function () {
            var oldTodos = $scope.todos;
            $scope.todos = [];
            angular.forEach(oldTodos, function (todo) {
                if (!todo.done) $scope.todos.push(todo);
            });
        };
    }]);