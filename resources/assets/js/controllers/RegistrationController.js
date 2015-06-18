angular.module('skApp.RegistrationController', [])
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

    self.capitalizeWords = function() {
        self.participation.name = self.participation.name.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
    };

    var defaultModelValues = {
        eventYear: new Date().getFullYear(),
        gender: 'V',
        comment: '',
        acceptExisting: true,
    };

    self.checkExistingParticipantCache = {};

    self.recentlyRegistered = IndividualService.query({eventYear: 2015, sort: '-created_at'});
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
        return $http.get('/teams', {
            params: {
                name: teamName,
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
        var res = new IndividualService(self.participation);

        var currentNumber = self.participation.number;

        res.$save()
           .then(function(response) {
                self.recentlyRegistered.unshift(response);
                if (self.recentlyRegistered.length > 10) {
                    self.recentlyRegistered.splice(10, 9999);
                }
            }, function(response) {
                self.regForm[response.data.error.field].$setValidity('unique', false);
            });

        self.reset();
        self.participation.number = currentNumber+1;
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

    self.checkExistingParticipant = function() {
        if (
            (typeof self.participation.name !== 'undefined'
            && typeof self.participation.birthYear !== 'undefined'
            && typeof self.participation.eventYear !== 'undefined')
            && (self.checkExistingParticipantCache.name !== self.participation.name
            || self.checkExistingParticipantCache.birthYear !== self.participation.birthYear
            || self.checkExistingParticipantCache.eventYear !== self.participation.eventYear)
        ) {
            self.checkExistingParticipantCache.name      = self.participation.name;
            self.checkExistingParticipantCache.birthYear = self.participation.birthYear;
            self.checkExistingParticipantCache.eventYear = self.participation.eventYear;

            $http({
                method: 'GET',
                url: '/10km',
                params: {
                    name: self.participation.name,
                    birthYear: self.participation.birthYear,
                    eventYear: self.participation.eventYear,
                },
            }).then(function(response) {
                // In case where element is found mark it as not unique
                if (typeof response.data[0] !== 'undefined') {
                    self.participation.acceptExisting = false;
                } else {
                    self.participation.acceptExisting = true;
                    self.regForm.acceptExisting.$setPristine();
                }
            });
        }
    };
}]);
