angular.module('skApp.ParticipantController', [])
.controller('ParticipantController', ['$routeSegment', '$window', '$http', 'IndividualService', function($routeSegment, $window, $http, IndividualService) {
    var self = this;

    self.participant = IndividualService.get({
        id: $routeSegment.$routeParams.participantId,
    });

    self.back = function() {
        $window.history.back();
    };

    self.loadItems = function(teamName) {
        return $http.get('/teams', {
            params: {
                name: teamName,
            }
        }).then(function(response) {
            return response.data;
        });
    }

    self.updateParticipant = function() {
        self.participant = IndividualService.update(self.participant);
    }

    self.delete = function() {
        IndividualService.delete({id: self.participant.id}, function() {
            $window.location = '#/10km/participants/';
        });
    };

}]);
