angular.module('skApp.RegistrationController', [])
.controller('RegistrationController', ['$http', function($http) {
    var self = this;

    self.participation = {};

    self.getLocation = function(val) {
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

    self.onSelect = function ($item) {
        self.participation.birthYear = $item.birthYear;
        self.participation.gender = $item.gender;
    };

    self.registerparticipant = function() {
        console.log(self.participation);
    };
}]);
