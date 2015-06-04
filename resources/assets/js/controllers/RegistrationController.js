angular.module('skApp.RegistrationController', [])
.filter('dateYear', function () {
    return function (input) {
        return new Date(input).getFullYear();
    }
})
.controller('RegistrationController', ['$http', function($http) {
    var self = this;

    var defaultModelValues = {
        eventYear: new Date().getFullYear(),
        gender: 'V',
    };

    self.participation = angular.copy(defaultModelValues);

    self.getExistingParticipants = function(val) {
        return $http.get('/participants', {
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
        self.participation.birthYear = new Date($item.birthYear).getFullYear();
    };

    self.registerParticipant = function() {
        console.log(self.participation);
    };

    self.reset = function() {
        self.participation = angular.copy(defaultModelValues);
        self.regForm.$setPristine();
    };
}]);
