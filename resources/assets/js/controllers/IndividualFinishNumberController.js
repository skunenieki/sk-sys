angular.module('skApp.IndividualFinishNumberController', [])
.controller('IndividualFinishNumberController', ['$http', 'IndividualFinishNumberService', function($http, IndividualFinishNumberService) {
    var self = this;
    var _ = require('underscore');

    self.manualNumber = null;
    self.finishes     = [];

    self.deleteFinishNumber = function(idx) {
        IndividualFinishNumberService.delete({id: self.finishes[idx].id}, function() {
            self.finishes.splice(idx, 1);
        });
    };

    self.markFinish = function(idx, row) {
        var finish = new IndividualFinishNumberService({
            number: self.manualNumber,
            manual: true,
        });

        finish.$save()
           .then(function(response) {
                self.manualNumber = null;
                self.finishes.unshift(response);
            }, function(response) {
                // Failure
            });
    };

    self.updateFinishes = function() {
        self.finishes = IndividualFinishNumberService.query();
    };

    self.updateFinishes();
}]);
