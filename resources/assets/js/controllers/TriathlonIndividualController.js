angular.module('skApp.TriathlonIndividualController', [])
.controller('TriathlonIndividualController', ['$routeSegment', '$http', 'TriathlonService', function($routeSegment, $http, TriathlonService) {
    var self = this;

    self.participants = TriathlonService.query({
        eventYear: $routeSegment.$routeParams.eventYear
    });
}]);
