angular.module('skApp.TriathlonTeamsController', [])
.controller('TriathlonTeamsController', ['$routeSegment', '$http', 'TriathlonTeamService', function($routeSegment, $http, TriathlonTeamService) {
    var self = this;

    self.teams = TriathlonTeamService.query({
        eventYear: $routeSegment.$routeParams.eventYear
    });
}]);
