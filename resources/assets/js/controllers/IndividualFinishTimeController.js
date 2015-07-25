angular.module('skApp.IndividualFinishTimeController', [])
.controller('IndividualFinishTimeController', ['IndividualFinishTimeService', function(IndividualFinishTimeService) {
    var self = this;

    self.finishTimes = IndividualFinishTimeService.query({});

    self.finish = function () {
        var turn = new IndividualFinishTimeService();

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
        IndividualFinishTimeService.update(self.finishTimes[idx]);
    };

    self.deleteFinishTime = function(idx) {
        IndividualFinishTimeService.delete({id: self.finishTimes[idx].id}, function() {
            self.finishTimes.splice(idx, 1);
        });
    };
}]);
