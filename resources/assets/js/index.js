var $         = require('jquery');
window.jQuery = $;
window.$      = $;

require('angular/angular.min');
require('angular/angular.min');
require('angular-resource/angular-resource.min');
require('angular-route/angular-route.min');
require('angular-bootstrap/dist/ui-bootstrap');
require('./vendor/angular-route-segment');
require('bootstrap/js/dropdown');
require('bootstrap/js/transition');
require('bootstrap/js/collapse');
require('ng-tags-input');
require('./partials');

require('./services/EventService');
require('./services/OptionService');
require('./services/IndividualService');
require('./services/OptionStateService');
require('./services/ParticipantService');
require('./services/IndividualTurnService');
require('./services/PersistentStateService');
require('./services/IndividualYearsService');
require('./services/IndividualFinishTimeService');
require('./services/IndividualFinishNumberService');

require('./directives/ngRemoteValidate');

require('./controllers/MainController');
require('./controllers/EventController');
require('./controllers/IndividualController');
require('./controllers/ParticipantController');
require('./controllers/RegistrationController');
require('./controllers/IndividualTurnController');
require('./controllers/IndividualStartController');
require('./controllers/RecentlyRegisteredConrtoller');
require('./controllers/IndividualFinishTimeController');
require('./controllers/IndividualFinishNumberController');

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

        'ui.bootstrap',
        'route-segment',
        'view-segment',
        'ngTagsInput',

        'skApp.ngRemoteValidate',

        'skApp.EventService',
        'skApp.OptionService',
        'skApp.IndividualService',
        'skApp.OptionStateService',
        'skApp.ParticipantService',
        'skApp.IndividualTurnService',
        'skApp.PersistentStateService',
        'skApp.IndividualYearsService',
        'skApp.IndividualFinishTimeService',
        'skApp.IndividualFinishNumberService',

        'skApp.MainController',
        'skApp.EventController',
        'skApp.IndividualController',
        'skApp.ParticipantController',
        'skApp.RegistrationController',
        'skApp.IndividualTurnController',
        'skApp.IndividualStartController',
        'skApp.RecentlyRegisteredConrtoller',
        'skApp.IndividualFinishTimeController',
        'skApp.IndividualFinishNumberController',
    ])
    .config(['$routeSegmentProvider', '$routeProvider', function($routeSegmentProvider, $routeProvider) {
        $routeSegmentProvider
            .when('/', 'home')
            .segment('home', {
                templateUrl: 'home.html',
            })

            .when('/10km', '10km')
            .when('/10km/participants', '10km.participants')
            .when('/10km/participants/:eventYear', '10km.participants.year')
            .when('/10km/participants/:participantId/edit', '10km.participants.edit')
            .when('/10km/registration', '10km.registration')
            .when('/10km/start', '10km.start')
            .when('/10km/turn', '10km.turn')
            .when('/10km/turn/grid', '10km.turn.grid')
            .when('/10km/turn/table', '10km.turn.table')
            .when('/10km/finish', '10km.finish')
            .when('/10km/finish/input', '10km.finish.input')
            .when('/10km/finish/edit', '10km.finish.edit')
            .when('/10km/chronometer', '10km.chronometer')
            .when('/10km/chronometer/input', '10km.chronometer.input')
            .when('/10km/chronometer/edit', '10km.chronometer.edit')
            .when('/10km/results', '10km.results')
            .when('/10km/diplomas', '10km.diplomas')
            .segment('10km', {
                templateUrl: '10km/main.html',
            })
            .within()
                .segment('participants', {
                    templateUrl: '10km/participants.html',
                })
                .within()
                    .segment('year', {
                        templateUrl: '10km/participant-table.html',
                        dependencies: ['eventYear']
                    })
                    .segment('edit', {
                        templateUrl: '10km/participant-edit.html',
                        dependencies: ['participantId']
                    })
                    .up()
                .segment('registration', {
                    templateUrl: '10km/registration.html'
                })
                .segment('start', {
                    templateUrl: '10km/start.html'
                })
                .segment('turn', {
                    templateUrl: '10km/turn.html'
                })
                .within()
                    .segment('grid', {
                        templateUrl: '10km/turnGrid.html'
                    })
                    .segment('table', {
                        templateUrl: '10km/turnTable.html'
                    })
                    .up()
                .segment('finish', {
                    templateUrl: '10km/finish.html'
                })
                .within()
                    .segment('input', {
                        templateUrl: '10km/finish-input.html'
                    })
                    .segment('edit', {
                        templateUrl: '10km/finish-edit.html'
                    })
                    .up()
                .segment('chronometer', {
                    templateUrl: '10km/chronometer.html'
                })
                .within()
                    .segment('input', {
                        templateUrl: '10km/chronometer-input.html'
                    })
                    .segment('edit', {
                        templateUrl: '10km/chronometer-edit.html'
                    })
                    .up()
                .segment('results', {
                    templateUrl: '10km/results.html'
                })
                .segment('diplomas', {
                    templateUrl: '10km/diplomas.html'
                })
                .up()

            .when('/mtb', 'mtb')
            .when('/mtb/registration', 'mtb.registration')
            .when('/mtb/chronometer', 'mtb.chronometer')
            .segment('mtb', {
                templateUrl: 'mtb/main.html',
            })
            .within()
                .segment('registration', {
                    templateUrl: 'mtb/registration.html'
                })
                .segment('chronometer', {
                    templateUrl: 'mtb/chronometer.html'
                })
                .up()

            .when('/triathlon', 'triathlon')
            .when('/triathlon/registration', 'triathlon.registration')
            .when('/triathlon/turn', 'triathlon.turn')
            .when('/triathlon/finish', 'triathlon.finish')
            .when('/triathlon/chronometer', 'triathlon.chronometer')
            .segment('triathlon', {
                templateUrl: 'triathlon/main.html',
            })
            .within()
                .segment('registration', {
                    templateUrl: 'triathlon/registration.html'
                })
                .segment('turn', {
                    templateUrl: 'triathlon/turn.html'
                })
                .segment('finish', {
                    templateUrl: 'triathlon/finish.html'
                })
                .segment('chronometer', {
                    templateUrl: 'triathlon/chronometer.html'
                })
                .up()

            .when('/football', 'football')
            .when('/football/registration', 'football.registration')
            .segment('football', {
                templateUrl: 'football/main.html',
            })
            .within()
                .segment('registration', {
                    templateUrl: 'football/registration.html'
                })
                .up()

            .when('/archive', 'archive')
            .when('/archive/10km', 'archive.10km')
            .segment('archive', {
                templateUrl: 'archive/main.html',
            })
            .within()
                .segment('10km', {
                    templateUrl: 'archive/10km.html'
                })
                .up()
            .when('/settings', 'settings')
            .when('/settings/new', 'settings.new')
            .when('/settings/:eventYear', 'settings.eventyear')
            .when('/settings/:eventYear/10km', 'settings.eventyear.10km')
            .when('/settings/:eventYear/mtb', 'settings.eventyear.mtb')
            .when('/settings/:eventYear/triathlon', 'settings.eventyear.triathlon')
            .when('/settings/:eventYear/football', 'settings.eventyear.football')
            .segment('settings', {
                templateUrl: 'settings/settings.html',
            })
            .within()
                .segment('new', {
                     templateUrl: 'settings/new.html',
                })
                .segment('eventyear', {
                    templateUrl: 'settings/settingsYear.html',
                    dependencies: ['eventYear']
                })
                .within()
                    .segment('10km', {
                        templateUrl: 'settings/10km.html',
                    })
                    .segment('mtb', {
                         templateUrl: 'settings/mtb.html',
                    })
                    .segment('triathlon', {
                         templateUrl: 'settings/triathlon.html',
                    })
                    .segment('football', {
                         templateUrl: 'settings/football.html',
                    })
                    .up()
                .up()

        $routeProvider.otherwise({ redirectTo: '/' });
    }]);
