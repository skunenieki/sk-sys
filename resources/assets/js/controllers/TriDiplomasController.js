angular.module('skApp.TriDiplomasController', [])
.controller('TriDiplomasController', ['$routeSegment', '$http', 'ParticipantService', function($routeSegment, $http, ParticipantService) {
    var self = this;

    self.diplomas = [];

    $http.get('/triathlon/diplomas/'+$routeSegment.$routeParams.eventYear, {})
        .then(function(response){
            self.diplomas = response.data;
        });

    self.updateDative = function(group, idx) {
        console.log(self.diplomas[group][idx]);
        ParticipantService.update({
            id: self.diplomas[group][idx].participantId,
            nameInDative: self.diplomas[group][idx].nameInDative
        });
    };
}]);
