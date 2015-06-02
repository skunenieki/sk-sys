var $         = require('jquery');
window.jQuery = $;
window.$      = $;

require('angular/angular.min');
require('angular/angular.min');
require('angular-resource/angular-resource.min');
require('angular-route/angular-route.min');

require('./partials');

// require('./services/SomeService');

require('./controllers/MainController');

var AppConfig = angular.module('AppConfig', [])
    .provider('AppConfig', function () {
    var config = {};

    return {
        set: function (settings) {
            config = settings;
        },
        $get: function () {
            return config;
        }
    };
});

var skApp = angular.module('skApp', [
        'ngRoute',
        'ngResource',
        'AppConfig',
        'partialsModule',

        'skApp.MainController',
    ]);
