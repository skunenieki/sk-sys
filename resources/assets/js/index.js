var $         = require('jquery');
window.jQuery = $;
window.$      = $;

require('angular/angular.min');
require('angular/angular.min');
require('angular-resource/angular-resource.min');
require('angular-route/angular-route.min');
require('angular-bootstrap/ui-bootstrap');
require('./vendor/angular-route-segment');
require('bootstrap/js/dropdown');
require('bootstrap/js/transition');
require('bootstrap/js/collapse');
require('ng-tags-input');
require('./partials');

require('./services/MtbService');
require('./services/EventService');
require('./services/OptionService');
require('./services/TriathlonService');
require('./services/IndividualService');
require('./services/OptionStateService');
require('./services/ParticipantService');
require('./services/TriathlonTeamService');
require('./services/MtbFinishTimeService');
require('./services/IndividualTurnService');
require('./services/MtbFinishNumberService');
require('./services/PersistentStateService');
require('./services/IndividualYearsService');
require('./services/TriathlonFinishTimeService');
require('./services/IndividualFinishTimeService');
require('./services/TriathlonFinishNumberService');
require('./services/IndividualFinishNumberService');

require('./directives/ngRemoteValidate');
require('./directives/ngReallyClick');

require('./controllers/MtbController');
require('./controllers/MainController');
require('./controllers/EventController');
require('./controllers/IndividualController');
require('./controllers/ParticipantController');
require('./controllers/RegistrationController');
require('./controllers/MtbFinishTimeController');
require('./controllers/MtbParticipantController');
require('./controllers/IndividualTurnController');
require('./controllers/TriathlonTeamsController');
require('./controllers/MtbFinishNumberController');
require('./controllers/MtbRegistrationController');
require('./controllers/IndividualStartController');
require('./controllers/TriRegistrationController');
require('./controllers/TriTeamParticipantController');
require('./controllers/IndividualDiplomasController');
require('./controllers/TriathlonIndividualController');
require('./controllers/TriTeamRegistrationController');
require('./controllers/TriathlonFinishTimeController');
require('./controllers/IndividualFinishTimeController');
require('./controllers/IndividualFinishNumberController');
require('./controllers/TriathlonFinishNumberController');
require('./controllers/TriIndividualRegistrationController');
require('./controllers/TriathlonIndividualParticipantController');

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

        // Directives
        'skApp.ngReallyClick',
        'skApp.ngRemoteValidate',

        // Services
        'skApp.MtbService',
        'skApp.MtbFinishTimeService',
        'skApp.MtbFinishNumberService',

        'skApp.EventService',
        'skApp.OptionService',
        'skApp.OptionStateService',

        'skApp.IndividualService',
        'skApp.IndividualTurnService',
        'skApp.PersistentStateService',
        'skApp.IndividualYearsService',
        'skApp.IndividualFinishTimeService',
        'skApp.IndividualFinishNumberService',

        'skApp.ParticipantService',

        'skApp.TriathlonService',
        'skApp.TriathlonTeamService',
        'skApp.TriathlonFinishTimeService',
        'skApp.TriathlonFinishNumberService',

        // Controllers
        'skApp.MainController',
        'skApp.EventController',

        'skApp.MtbController',
        'skApp.MtbFinishTimeController',
        'skApp.MtbParticipantController',
        'skApp.MtbRegistrationController',
        'skApp.MtbFinishNumberController',

        'skApp.IndividualController',
        'skApp.IndividualTurnController',
        'skApp.IndividualStartController',
        'skApp.IndividualDiplomasController',
        'skApp.IndividualFinishTimeController',
        'skApp.IndividualFinishNumberController',

        'skApp.ParticipantController',
        'skApp.RegistrationController',

        'skApp.TriathlonTeamsController',
        'skApp.TriRegistrationController',
        'skApp.TriTeamParticipantController',
        'skApp.TriathlonFinishTimeController',
        'skApp.TriTeamRegistrationController',
        'skApp.TriathlonIndividualController',
        'skApp.TriathlonFinishNumberController',
        'skApp.TriIndividualRegistrationController',
        'skApp.TriathlonIndividualParticipantController',
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
            .when('/10km/diplomas/:eventYear', '10km.diplomas.year')
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
                .within()
                    .segment('year', {
                        templateUrl: '10km/diplomas-year.html',
                        dependencies: ['eventYear']
                    })
                    .up()
                .up()

            .when('/mtb', 'mtb')
            .when('/mtb/registration', 'mtb.registration')
            .when('/mtb/finish', 'mtb.finish')
            .when('/mtb/finish/input', 'mtb.finish.input')
            .when('/mtb/finish/edit', 'mtb.finish.edit')
            .when('/mtb/chronometer', 'mtb.chronometer')
            .when('/mtb/chronometer/input', 'mtb.chronometer.input')
            .when('/mtb/chronometer/edit', 'mtb.chronometer.edit')
            .when('/mtb/participants', 'mtb.participants')
            .when('/mtb/participants/:eventYear', 'mtb.participants.year')
            .when('/mtb/participants/:participantId/edit', 'mtb.participants.edit')
            .when('/mtb/results', 'mtb.results')
            .when('/mtb/diplomas', 'mtb.diplomas')
            .segment('mtb', {
                templateUrl: 'mtb/main.html',
            })
            .within()
                .segment('registration', {
                    templateUrl: 'mtb/registration.html'
                })
                .segment('participants', {
                    templateUrl: 'mtb/participants.html',
                })
                .within()
                    .segment('year', {
                        templateUrl: 'mtb/participant-table.html',
                        dependencies: ['eventYear']
                    })
                    .segment('edit', {
                        templateUrl: 'mtb/participant-edit.html',
                        dependencies: ['participantId']
                    })
                    .up()
                .segment('chronometer', {
                    templateUrl: 'mtb/chronometer.html'
                })
                .within()
                    .segment('input', {
                        templateUrl: 'mtb/chronometer-input.html'
                    })
                    .segment('edit', {
                        templateUrl: 'mtb/chronometer-edit.html'
                    })
                    .up()
                .segment('finish', {
                    templateUrl: 'mtb/finish.html'
                })
                .within()
                    .segment('input', {
                        templateUrl: 'mtb/finish-input.html'
                    })
                    .segment('edit', {
                        templateUrl: 'mtb/finish-edit.html'
                    })
                    .up()
                .segment('results', {
                    templateUrl: 'mtb/results.html'
                })
                .segment('diplomas', {
                    templateUrl: 'mtb/diplomas.html',
                })
                .up()

            .when('/triathlon', 'triathlon')
            .when('/triathlon/registration', 'triathlon.registration')
            .when('/triathlon/registration/individual', 'triathlon.registration.individual')
            .when('/triathlon/registration/team', 'triathlon.registration.team')
            .when('/triathlon/turn', 'triathlon.turn')
            .when('/triathlon/finish', 'triathlon.finish')
            .when('/triathlon/finish/input', 'triathlon.finish.input')
            .when('/triathlon/finish/edit', 'triathlon.finish.edit')
            .when('/triathlon/chronometer', 'triathlon.chronometer')
            .when('/triathlon/chronometer/input', 'triathlon.chronometer.input')
            .when('/triathlon/chronometer/edit', 'triathlon.chronometer.edit')
            .when('/triathlon/participants', 'triathlon.participants')
            .when('/triathlon/participants/:eventYear', 'triathlon.participants.year')
            .when('/triathlon/participants/:participantId/edit', 'triathlon.participants.edit')
            .when('/triathlon/teams', 'triathlon.teams')
            .when('/triathlon/teams/:eventYear', 'triathlon.teams.year')
            .when('/triathlon/teams/:participantId/edit', 'triathlon.teams.edit')
            .when('/triathlon/results', 'triathlon.results')
            .segment('triathlon', {
                templateUrl: 'triathlon/main.html',
            })
            .within()
                .segment('registration', {
                    templateUrl: 'triathlon/registration.html'
                })
                .within()
                    .segment('individual', {
                        templateUrl: 'triathlon/registration-individual.html'
                    })
                    .segment('team', {
                        templateUrl: 'triathlon/registration-team.html'
                    })
                    .up()
                .segment('turn', {
                    templateUrl: 'triathlon/turn.html'
                })
                .segment('chronometer', {
                    templateUrl: 'triathlon/chronometer.html'
                })
                .within()
                    .segment('input', {
                        templateUrl: 'triathlon/chronometer-input.html'
                    })
                    .segment('edit', {
                        templateUrl: 'triathlon/chronometer-edit.html'
                    })
                    .up()
                .segment('finish', {
                    templateUrl: 'triathlon/finish.html'
                })
                .within()
                    .segment('input', {
                        templateUrl: 'triathlon/finish-input.html'
                    })
                    .segment('edit', {
                        templateUrl: 'triathlon/finish-edit.html'
                    })
                    .up()
                .segment('participants', {
                    templateUrl: 'triathlon/participants.html'
                })
                .within()
                    .segment('year', {
                        templateUrl: 'triathlon/participant-table.html',
                        dependencies: ['eventYear']
                    })
                    .segment('edit', {
                        templateUrl: 'triathlon/participant-edit.html',
                        dependencies: ['participantId']
                    })
                    .up()
                .segment('teams', {
                    templateUrl: 'triathlon/teams.html'
                })
                .within()
                    .segment('year', {
                        templateUrl: 'triathlon/team-table.html',
                        dependencies: ['eventYear']
                    })
                    .segment('edit', {
                        templateUrl: 'triathlon/teams-edit.html',
                        dependencies: ['participantId']
                    })
                    .up()
                .segment('results', {
                    templateUrl: 'triathlon/results.html'
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
