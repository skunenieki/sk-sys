angular.module('skApp.MtbFinishTimeController', [])
.controller('MtbFinishTimeController', ['MtbFinishTimeService', 'PersistentStateService', function(MtbFinishTimeService, PersistentStateService) {
    var self = this;

    self.finishTimes = MtbFinishTimeService.query({});

    self.finish = function () {
        var turn = new MtbFinishTimeService({
            time: PersistentStateService.timer()
        });

        turn.$save()
           .then(function(response) {
                self.finishTimes.unshift(response);
            }, function(response) {
                // Failure
            });
    };

    self.toogleState = function(idx) {
        self.finishTimes[idx].disabled = !self.finishTimes[idx].disabled;
        console.log(self.finishTimes[idx]);
        MtbFinishTimeService.update(self.finishTimes[idx]);
    };

    self.deleteFinishTime = function(idx) {
        MtbFinishTimeService.delete({id: self.finishTimes[idx].id}, function() {
            self.finishTimes.splice(idx, 1);
        });
    };
}]);
