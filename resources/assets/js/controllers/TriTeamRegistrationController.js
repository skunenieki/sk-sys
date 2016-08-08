angular.module('skApp.TriTeamRegistrationController', [])
.controller('TriTeamRegistrationController', ['$http', 'TriathlonTeamService', function($http, TriathlonTeamService) {
    var self = this;

    self.isTeam = true;
    self.recentlyRegistered = TriathlonTeamService.query({eventYear: 2016, sort: '-created_at'});

    self.capitalizeSwimmerWords = function() {
        self.participation.swimmer.name = self.participation.swimmer.name.replace(/(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g, function(firstLetter) {
            return firstLetter.toUpperCase();
        });
    };

    self.capitalizeBikerWords = function() {
        self.participation.biker.name = self.participation.biker.name.replace(/(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g, function(firstLetter) {
            return firstLetter.toUpperCase();
        });
    };

    self.capitalizeRunnerWords = function() {
        self.participation.runner.name = self.participation.runner.name.replace(/(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g, function(firstLetter) {
            return firstLetter.toUpperCase();
        });
    };

    var defaultModelValues = {
        eventYear: new Date().getFullYear(),
        comment: '',
    };

    self.checkExistingParticipantCache = {};
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

    self.onSelectSwimmer = function ($item) {
        self.participation.swimmer.name      = $item.name;
        self.participation.swimmer.gender    = $item.gender;
        self.participation.swimmer.birthYear = $item.birthYear;
    };

    self.onSelectBiker = function ($item) {
        self.participation.biker.name      = $item.name;
        self.participation.biker.gender    = $item.gender;
        self.participation.biker.birthYear = $item.birthYear;
    };

    self.onSelectRunner = function ($item) {
        self.participation.runner.name      = $item.name;
        self.participation.runner.gender    = $item.gender;
        self.participation.runner.birthYear = $item.birthYear;
    };

    self.registerTeam = function() {
        console.log(self.participation);
        var res = new TriathlonTeamService(self.participation);

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
        self.recentlyRegistered = TriathlonTeamService.query({
            eventYear: 2016,
            sort: '-created_at',
            limit: self.recentlyRegistered.length+10
        });
    };

    self.delete = function(idx) {
        TriathlonTeamService.delete({id: self.recentlyRegistered[idx].id}, function() {
            self.recentlyRegistered.splice(idx, 1);
        });
    };
}]);
