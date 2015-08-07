angular.module('skApp.TriathlonFinishNumberController', [])
.controller('TriathlonFinishNumberController', ['$interval', '$http', 'TriathlonFinishNumberService', 'PersistentStateService', function($interval, $http, TriathlonFinishNumberService, PersistentStateService) {
    var self = this;
    var _ = require('underscore');

    self.manualNumber       = null;
    self.finishes           = [];
    self.potentialFinishers = [];

    self.deleteFinishNumber = function(idx) {
        TriathlonFinishNumberService.delete({id: self.finishes[idx].id}, function() {
            self.finishes.splice(idx, 1);
        });
    };

    self.markFinish = function(idx) {
        var number = null;
        var manual = false;


        if (false !== idx) {
            number = self.potentialFinishers[idx].number;
        } else if (false === idx && self.manualNumber !== null) {
            number = self.manualNumber;
            manual = true;
        }

        var finish = new TriathlonFinishNumberService({
            number: number,
            manual: manual,
        });

        finish.$save()
           .then(function(response) {
                if (idx !== false) {
                    self.potentialFinishers.splice(idx, 1);
                }

                if (self.manualNumber !== null) {
                    self.manualNumber = null;
                }

                self.finishes.unshift(response);
            }, function(response) {
                // Failure
            });
    };

    self.updatePotentialFinishers = function() {
        return $http.get('/triathlon/finish', {})
            .then(function(response){
                for (var i = 0; i < response.data.length; i++) {
                    var finisherIndex = _.indexOf(self.potentialFinishers, _.find(self.potentialFinishers, function(finisher) { return finisher.number == response.data[i].number }));
                    if (-1 === finisherIndex) {
                        self.potentialFinishers.push(response.data[i]);
                    } else {
                        // self.potentialFinishers[finisherIndex].startInSeconds = response.data[i].startInSeconds;
                    }
                }

                for (var i = self.potentialFinishers.length - 1; i >= 0; i--) {
                    if (typeof _.find(response.data, function(finisher) { return finisher.number == self.potentialFinishers[i].number }) === 'undefined') {
                        self.potentialFinishers.splice(i, 1);
                    }
                };

                self.potentialFinishers.sort(function(a, b) {
                    return a.number - b.number;
                });
            });
    };

    if (false === PersistentStateService.potentialFinishersTimer) {
        PersistentStateService.potentialFinishersTimer = $interval(self.updatePotentialFinishers, 5000);
    }

    self.updatePotentialFinishers();

    self.updateFinishes = function() {
        self.finishes = TriathlonFinishNumberService.query();
    };

    self.updateFinishes();
}]);
