angular.module('skApp.IndividualFinishTimeController', [])
.controller('IndividualFinishTimeController', ['IndividualFinishTimeService', 'PersistentStateService', function(IndividualFinishTimeService, PersistentStateService) {
    var self = this;

    self.finishTimes = IndividualFinishTimeService.query({});

    self.finish = function () {
        var finish = new IndividualFinishTimeService({
            time: PersistentStateService.timer()
        });

        finish.$save()
           .then(function(response) {
                self.finishTimes.unshift(response);
            }, function(response) {
                // Failure
            });
    };

    self.toogleState = function(idx) {
        self.finishTimes[idx].disabled = !self.finishTimes[idx].disabled;
        console.log(self.finishTimes[idx]);
        IndividualFinishTimeService.update(self.finishTimes[idx]);
    };

    self.deleteFinishTime = function(idx) {
        IndividualFinishTimeService.delete({id: self.finishTimes[idx].id}, function() {
            self.finishTimes.splice(idx, 1);
        });
    };
}]);
