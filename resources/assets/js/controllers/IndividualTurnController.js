angular.module('skApp.IndividualTurnController', [])
.controller('IndividualTurnController', ['$interval', 'IndividualTurnService', function($interval, IndividualTurnService) {
    var self = this;
    var _ = require('underscore');

    self.turns  = [];
    self.turned = [];

    self.setTurn = function(idx) {
        var id     = null;
        var number = null;

        if (false !== idx) {
            id     = self.turns[idx].id;
            number = self.turns[idx].number;
        }

        var turn = new IndividualTurnService({
            id: id,
            number: number
        });

        turn.$save()
           .then(function(response) {
                if (idx !== false) {
                    self.turns.splice(idx, 1);
                }
                self.turned.unshift(response);
            }, function(response) {
                // Failure
            });
    };

    self.loadPotentialTurns = function() {
        return IndividualTurnService.query();
    };

    self.updatePotentialTurns = function() {
        var turns = IndividualTurnService.query({}, function(response) {
            for (var i = 0; i < response.length; i++) {
                var turnIndex = _.indexOf(self.turns, _.find(self.turns, function(turn) { return turn.number == response[i].number }));
                if (-1 === turnIndex) {
                    self.turns.push(response[i]);
                } else {
                    self.turns[turnIndex].startInSeconds = response[i].startInSeconds;
                }
            }

            for (var i = self.turns.length - 1; i >= 0; i--) {
                if (typeof _.find(response, function(turn) { return turn.number == self.turns[i].number }) === 'undefined') {
                    self.turns.splice(i, 1);
                }
            };

            self.turns.sort(function(a, b) {
                return a.startInSeconds - b.startInSeconds;
            });
        });
    };

    $interval(self.updatePotentialTurns, 5000);

    self.updatePotentialTurns();
}]);
