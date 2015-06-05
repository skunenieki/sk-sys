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
        IndividualService.update(self.participant);
    }

}]);
