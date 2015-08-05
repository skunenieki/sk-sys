angular.module('skApp.TriathlonFinishTimeController', [])
.controller('TriathlonFinishTimeController', ['TriathlonFinishTimeService', 'PersistentStateService', function(TriathlonFinishTimeService, PersistentStateService) {
    var self = this;

    self.finishTimes = TriathlonFinishTimeService.query({});

    self.finish = function () {
        var finish = new TriathlonFinishTimeService({
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
        TriathlonFinishTimeService.update(self.finishTimes[idx]);
    };

    self.deleteFinishTime = function(idx) {
        TriathlonFinishTimeService.delete({id: self.finishTimes[idx].id}, function() {
            self.finishTimes.splice(idx, 1);
        });
    };
}]);
