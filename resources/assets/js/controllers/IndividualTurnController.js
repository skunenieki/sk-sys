angular.module('skApp.IndividualTurnController', [])
.controller('IndividualTurnController', ['IndividualTurnService', 'IndividualService', function(IndividualTurnService, IndividualService) {
    var self = this;
    var moment = require('moment');

    self.turn = IndividualTurnService.query();

    self.setTurn = function(idx) {

        self.individualStartTime = '2015-07-14T20:00:00+03:00';

        var turnTime = moment().diff(
            moment(self.individualStartTime),
            'seconds'
        );

        self.turn[idx].turn = self.sec2humanReadable(turnTime);
        IndividualService.update(self.turn[idx], function(response) {
            self.turn.splice(idx, 1);
        });
    };

    self.sec2humanReadable = function(duration) {
        var hour = 0;
        var min = 0;
        var sec = 0;

        if (duration) {
            if (duration >= 60) {
                min = Math.floor(duration / 60);
                sec = duration % 60;
            } else {
                sec = duration;
            }

            if (min >= 60) {
                hour = Math.floor(min / 60);
                min = min - hour * 60;
            }

            if (hour < 10) {
                hour = '0'+hour;
            }

            if (min < 10) {
                min = '0'+min;
            }

            if (sec < 10) {
                sec = '0'+sec;
            }
        }

        return hour +":"+ min +":"+ sec;
    }

}]);
