angular.module('skApp.MtbDiplomasController', [])
.controller('MtbDiplomasController', ['$routeSegment', '$http', 'ParticipantService', function($routeSegment, $http, ParticipantService) {
    var self = this;

    self.diplomas = [];

    $http.get('/mtb/diplomas/'+$routeSegment.$routeParams.eventYear, {})
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
