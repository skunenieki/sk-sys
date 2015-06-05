angular.module('skApp.IndividualController', [])
.controller('IndividualController', ['$routeSegment', 'IndividualService', function($routeSegment, IndividualService) {
    var self = this;

    self.participants = IndividualService.query({
        eventYear: $routeSegment.$routeParams.eventYear,
        limit: 10000,
        sort: 'number'
    });

}]);
