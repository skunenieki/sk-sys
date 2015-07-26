angular.module('skApp.MtbParticipantController', [])
.controller('MtbParticipantController', ['$routeSegment', '$window', '$http', 'MtbService', function($routeSegment, $window, $http, MtbService) {
    var self = this;

    self.participant = MtbService.get({
        id: $routeSegment.$routeParams.participantId,
    });

    self.back = function() {
        $window.history.back();
    };

    self.updateParticipant = function() {
        self.participant = MtbService.update(self.participant);
    }

    self.delete = function() {
        MtbService.delete({id: self.participant.id}, function() {
            $window.location = '#/mtb/participants/';
        });
    };

}]);
