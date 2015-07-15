angular.module('skApp.IndividualTurnController', [])
.controller('IndividualTurnController', ['$interval', '$http', 'IndividualTurnService', function($interval, $http, IndividualTurnService) {
    var self = this;
    var _ = require('underscore');

    // TODO: Add processing indication when someone hits the number button

    self.manualNumber = null;

    self.turns  = [];
    self.turned = IndividualTurnService.query();

    self.setTurn = function(idx) {
        var number = null;
        var manual = false;


        if (false !== idx) {
            number = self.turns[idx].number;
        } else if (false === idx && self.manualNumber !== null) {
            number = self.manualNumber;
            manual = true;
        }

        var turn = new IndividualTurnService({
            number: number,
            manual: manual,
        });

        turn.$save()
           .then(function(response) {
                if (idx !== false) {
                    self.turns.splice(idx, 1);
                }

                if (self.manualNumber !== null) {
                    self.manualNumber = null;
                }

                self.turned.unshift(response);
            }, function(response) {
                // Failure
            });
    };

    self.updatePotentialTurns = function() {
        return $http.get('/10km/turn', {})
            .then(function(response){
                for (var i = 0; i < response.data.length; i++) {
                    var turnIndex = _.indexOf(self.turns, _.find(self.turns, function(turn) { return turn.number == response.data[i].number }));
                    if (-1 === turnIndex) {
                        self.turns.push(response.data[i]);
                    } else {
                        self.turns[turnIndex].startInSeconds = response.data[i].startInSeconds;
                    }
                }

                for (var i = self.turns.length - 1; i >= 0; i--) {
                    if (typeof _.find(response.data, function(turn) { return turn.number == self.turns[i].number }) === 'undefined') {
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
