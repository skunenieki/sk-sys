angular.module('skApp.RegistrationController', [])
.controller('RegistrationController', ['$http', function($http) {
    var self = this;

    self.participation = {};

    self.getExistingParticipants = function(val) {
        return $http.get('/stubs/participants.json', {
            params: {
                name: val,
            }
        }).then(function(response){
            return response.data.map(function(item){
                return item;
            });
        });
    }

    self.loadItems = function(teamName) {
        return $http.get('/stubs/teams.json', {
            params: {
                team: teamName,
            }
        }).then(function(response) {
            return response.data;
        });
    }

    self.onSelect = function ($item) {
        self.participation.name      = $item.name;
        self.participation.gender    = $item.gender;
        self.participation.birthYear = $item.birthYear;
    };

    self.registerParticipant = function() {
        console.log(self.participation);
    };

    self.reset = function() {
        self.participation = {};
        self.regForm.$setPristine();
    };
}]);
