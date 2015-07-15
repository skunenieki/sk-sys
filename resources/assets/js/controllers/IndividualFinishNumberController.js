angular.module('skApp.IndividualFinishNumberController', [])
.controller('IndividualFinishNumberController', ['$interval', '$http', 'IndividualFinishNumberService', function($interval, $http, IndividualFinishNumberService) {
    var self = this;
    var _ = require('underscore');

    self.manualNumber       = null;
    self.finished           = IndividualFinishNumberService.query();;
    self.potentialFinishers = [];

    self.markFinish = function(idx) {
        var number = null;
        var manual = false;


        if (false !== idx) {
            number = self.potentialFinishers[idx].number;
        } else if (false === idx && self.manualNumber !== null) {
            number = self.manualNumber;
            manual = true;
        }

        var finish = new IndividualFinishNumberService({
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

                self.finished.unshift(response);
            }, function(response) {
                // Failure
            });
    };

    self.updatePotentialFinishers = function() {
        return $http.get('/10km/finish', {})
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

                // self.potentialFinishers.sort(function(a, b) {
                //     return a.startInSeconds - b.startInSeconds;
                // });
            });
    };

    $interval(self.updatePotentialFinishers, 5000);
    self.updatePotentialFinishers();
}]);
