angular.module('skApp.EventYearController', [])
.controller('EventYearController', ['IndividualYearsService', function(IndividualYearsService) {
    var self = this;

    self.eventYears = IndividualYearsService.query();
}]);
