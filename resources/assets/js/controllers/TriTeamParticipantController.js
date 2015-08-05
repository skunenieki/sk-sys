angular.module('skApp.TriTeamParticipantController', [])
.controller('TriTeamParticipantController', ['$routeSegment', '$window', '$http', 'TriathlonTeamService', function($routeSegment, $window, $http, TriathlonTeamService) {
    var self = this;

    self.participant = TriathlonTeamService.get({
        id: $routeSegment.$routeParams.participantId,
    });

    self.back = function() {
        $window.history.back();
    };

    self.updateParticipant = function() {
        self.participant = TriathlonTeamService.update(self.participant);
    }

    self.delete = function() {
        TriathlonTeamService.delete({id: self.participant.id}, function() {
            $window.location = '#/trianthlon/teams/';
        });
    };

}]);
