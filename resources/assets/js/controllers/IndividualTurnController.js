angular.module('skApp.IndividualTurnController', [])
.controller('IndividualTurnController', ['$http', 'IndividualTurnService', function($http, IndividualTurnService) {
    var self = this;
    var _ = require('underscore');

    self.manualNumber = null;
    self.turns        = [];

    self.deleteTurn = function(idx) {
        IndividualTurnService.delete({id: self.turns[idx].id}, function() {
            self.turns.splice(idx, 1);
        });
    };

    self.setTurn = function(idx, row) {
        var turn = new IndividualTurnService({
            number: self.manualNumber,
            manual: true,
        });

        turn.$save()
           .then(function(response) {
                self.manualNumber = null;
                self.turns.unshift(response);
            }, function(response) {
                // Failure
            });
    };

    self.updateTurns = function() {
        self.turns = IndividualTurnService.query();
    };

    self.updateTurns();
}]);
