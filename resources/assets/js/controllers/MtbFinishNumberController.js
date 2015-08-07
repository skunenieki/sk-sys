angular.module('skApp.MtbFinishNumberController', [])
.controller('MtbFinishNumberController', ['$interval', '$http', 'MtbFinishNumberService', 'PersistentStateService', function($interval, $http, MtbFinishNumberService, PersistentStateService) {
    var self = this;
    var _ = require('underscore');

    self.manualNumber       = null;
    self.finishes           = [];
    self.potentialFinishers = [];

    self.deleteFinishNumber = function(idx) {
        MtbFinishNumberService.delete({id: self.finishes[idx].id}, function() {
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

        var finish = new MtbFinishNumberService({
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
        return $http.get('/mtb/finish', {})
            .then(function(response){
                for (var i = 0; i < response.data.length; i++) {
                    var finisherIndex = _.indexOf(self.potentialFinishers, _.find(self.potentialFinishers, function(finisher) { return finisher.number == response.data[i].number }));
                    if (-1 === finisherIndex) {
                        self.potentialFinishers.push(response.data[i]);
                    }
                }

                // for (var i = self.potentialFinishers.length - 1; i >= 0; i--) {
                //     if (typeof _.find(response.data, function(finisher) { return finisher.number == self.potentialFinishers[i].number }) === 'undefined') {
                //         self.potentialFinishers.splice(i, 1);
                //     }
                // };

                self.potentialFinishers.sort(function(a, b) {
                    return a.number - b.number;
                });
            });
    };

    if (false === PersistentStateService.potentialFinishersTimer) {
        PersistentStateService.potentialFinishersTimer = $interval(self.updatePotentialFinishers, 1500);
    }

    self.updatePotentialFinishers();

    self.updateFinishes = function() {
        self.finishes = MtbFinishNumberService.query();
    };

    self.updateFinishes();
}]);
