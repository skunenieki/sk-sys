angular.module('skApp.RegistrationController', [])
.filter('dateYear', function () {
    return function (input) {
        return new Date(input).getFullYear();
    }
})
.directive('ngReallyClick', [function() {
    return {
        restrict: 'A',
        link: function(scope, element, attrs) {
            element.bind('click', function() {
                var message = attrs.ngReallyMessage;
                if (message && confirm(message)) {
                    scope.$apply(attrs.ngReallyClick);
                }
            });
        }
    }
}])
.controller('RegistrationController', ['$http', 'ParticipantService', 'IndividualService', function($http, ParticipantService, IndividualService) {
    var self = this;

    self.recentlyRegistered = IndividualService.query({eventYear: 2015, sort: '-created_at'});

    var defaultModelValues = {
        eventYear: new Date().getFullYear(),
        gender: 'V',
        comment: '',
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
        var res = new IndividualService(self.participation);

        res.$save()
           .then(function(response) {
                self.recentlyRegistered.unshift(response);
                if (self.recentlyRegistered.length > 10) {
                    self.recentlyRegistered.splice(10, 9999);
                }
            });
        self.reset();
    };

    self.reset = function() {
        self.participation = angular.copy(defaultModelValues);
        self.regForm.$setPristine();
    };

    self.loadMore = function() {
        self.recentlyRegistered = IndividualService.query({
            eventYear: 2015,
            sort: '-created_at',
            limit: self.recentlyRegistered.length+10
        });
    };

    self.delete = function(idx) {
        IndividualService.delete({id: self.recentlyRegistered[idx].id}, function() {
            self.recentlyRegistered.splice(idx, 1);
        });
    };
}]);
