angular.module('skApp.TriathlonIndividualParticipantController', [])
.controller('TriathlonIndividualParticipantController', ['$routeSegment', '$window', '$http', 'TriathlonService', function($routeSegment, $window, $http, TriathlonService) {
    var self = this;

    self.participant = TriathlonService.get({
        id: $routeSegment.$routeParams.participantId,
    });

    self.back = function() {
        $window.history.back();
    };

    self.updateParticipant = function() {
        self.participant = TriathlonService.update(self.participant);
    }

    self.delete = function() {
        TriathlonService.delete({id: self.participant.id}, function() {
            $window.location = '#/trianthlon/participants/';
        });
    };

}]);
