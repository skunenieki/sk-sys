angular.module('skApp.IndividualDiplomasController', [])
.controller('IndividualDiplomasController', ['$routeSegment', '$http', function($routeSegment, $http) {
    var self = this;

    self.diplomas = [];

    $http.get('/10km/diplomas/'+$routeSegment.$routeParams.eventYear, {})
        .then(function(response){
            self.diplomas = response.data;
        });
}]);
