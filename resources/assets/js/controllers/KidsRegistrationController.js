angular.module('skApp.KidsRegistrationController', [])
.controller('KidsRegistrationController', ['$http', 'KidsService', function($http, KidsService) {
    var self = this;

    self.capitalizeWords = function() {
        self.participation.name = self.participation.name.replace(/(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g, function(firstLetter) {
            return firstLetter.toUpperCase();
        });
    };

    var defaultModelValues = {
        eventYear: new Date().getFullYear(),
        gender: 'V',
        comment: '',
        acceptExisting: true,
    };

    self.checkExistingParticipantCache = {};

    self.recentlyRegistered = KidsService.query({eventYear: 2020, sort: '-created_at'}); // @todo 2020
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

    self.onSelect = function ($item) {
        self.participation.name      = $item.name;
        self.participation.gender    = $item.gender;
        self.participation.birthYear = $item.birthYear;
        self.checkExistingParticipant();
    };

    self.registerParticipant = function() {
        var res = new KidsService(self.participation);

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
        self.recentlyRegistered = KidsService.query({
            eventYear: 2020, // @todo 2020
            sort: '-created_at',
            limit: self.recentlyRegistered.length+10
        });
    };

    self.delete = function(idx) {
        KidsService.delete({id: self.recentlyRegistered[idx].id}, function() {
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

            console.log(self.participation.name);

            $http({
                method: 'GET',
                url: '/kids',
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
