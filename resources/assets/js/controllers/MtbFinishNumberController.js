angular.module('skApp.MtbFinishNumberController', [])
.controller('MtbFinishNumberController', ['$http', 'MtbFinishNumberService', function($http, MtbFinishNumberService) {
    var self = this;
    var _ = require('underscore');

    self.manualNumber = null;
    self.finishes     = [];

    self.deleteFinishNumber = function(idx) {
        MtbFinishNumberService.delete({id: self.finishes[idx].id}, function() {
            self.finishes.splice(idx, 1);
        });
    };

    self.markFinish = function(idx) {
        var finish = new MtbFinishNumberService({
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
        self.finishes = MtbFinishNumberService.query();
    };

    self.updateFinishes();
}]);
