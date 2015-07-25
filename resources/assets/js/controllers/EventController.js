angular.module('skApp.EventController', [])
.controller('EventController', ['$window', '$routeSegment', 'EventService', 'OptionService', 'OptionStateService', function($window, $routeSegment, EventService, OptionService, OptionStateService) {
    var self = this;
    var _ = require('underscore');

    self.eventSettings = [];
    self.event         = {};
    self.eventIdx      = null;
    self.newEvent      = {};
    self.events        = [];

    self.loadEvents = function() {
        self.events = EventService.query({}, function() {
            self.changeEventModel(false);
        });
    };

    self.changeEventModel = function(year) {
        if (false === year) {
            year = $routeSegment.$routeParams.eventYear;
        }

        self.eventIdx = _.indexOf(self.events, _.find(self.events, function(event) { return event.eventYear == year }));
        self.event = self.events[self.eventIdx];

        if (typeof self.event !== 'undefined') {
            _.each(self.event.settings, function(value, key) {
                self.event[key] = value;
            });
        }
    };

    self.saveEvent = function() {
        EventService.update(self.events[self.eventIdx]);
    };

    self.addEvent = function() {
        var res = new EventService(self.newEvent);

        res.$save()
           .then(function(response) {
                self.events.unshift(response);
                self.newEvent = {};
            }, function(response) {
            });
    };

    self.activateEvent = function (year) {
        OptionService.update({id: 'activeEventYear', value: year}, function(response) {
            OptionStateService.refreshOptions(true);
            $window.location.reload();
        });
    };

    self.deleteEvent = function (year) {
        EventService.delete({eventYear: year}, function(response) {
            // remove from event array here
        });
    };

    self.loadEvents();
}]);
