angular.module('skApp.MtbController', [])
.controller('MtbController', ['$routeSegment', 'MtbService', function($routeSegment, MtbService) {
    var self = this;

    self.participants = MtbService.query({
        eventYear: $routeSegment.$routeParams.eventYear,
        limit: 10000,
        sort: 'number'
    });

}]);
