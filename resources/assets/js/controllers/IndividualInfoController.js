angular.module('skApp.IndividualInfoController', [])
.controller('IndividualInfoController', ['$interval', 'IndividualService', 'PersistentStateService', function($interval, IndividualService, PersistentStateService) {
    var self = this;

    var _ = require('underscore');

    self.summary          = [];
    self.bestInGroups     = [];

    self.updateSummary = function() {
        IndividualService.query({
            eventYear: 2017,
            limit: 100000,
        }, function(data) {
            self.summary = data;

            for (var i = data.length - 1; i >= 0; i--) {
                var idx = _.indexOf(
                    self.bestInGroups,
                    _.find(self.bestInGroups, function(rw) {
                        return rw.group == data[i].group;
                    })
                );

		if (data[i].resultInSeconds > 0 && idx < 0) {
                    self.bestInGroups.push(data[i]);
                    continue;
                } else {
		    if (data[i].resultInSeconds > 0 && data[i].resultInSeconds < self.bestInGroups[idx].resultInSeconds) {
                        self.bestInGroups[idx] = data[i];
                    }
                }
            }
        });
    };

    self.updateSummary();
    if (false === PersistentStateService.bestSummaryTimer) {
        PersistentStateService.bestSummaryTimer = $interval(self.updateSummary, 5000);
    }
}]);
