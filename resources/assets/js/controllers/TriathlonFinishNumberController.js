angular.module('skApp.TriathlonFinishNumberController', [])
.controller('TriathlonFinishNumberController', ['$interval', '$http', 'TriathlonFinishNumberService', 'PersistentStateService', function($interval, $http, TriathlonFinishNumberService, PersistentStateService) {
    var self = this;
    var _ = require('underscore');

    self.manualNumber = null;
    self.finishes     = [];

    self.deleteFinishNumber = function(idx) {
        TriathlonFinishNumberService.delete({id: self.finishes[idx].id}, function() {
            self.finishes.splice(idx, 1);
        });
    };

    self.markFinish = function(idx, row) {
        var finish = new TriathlonFinishNumberService({
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
        self.finishes = TriathlonFinishNumberService.query();
    };

    self.updateFinishes();
}]);
