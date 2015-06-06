angular.module('skApp.IndividualStartController', [])
.controller('IndividualStartController', ['$interval', 'IndividualService', function($interval, IndividualService) {
    var self = this;
    var moment = require('moment');

    self.currentTime = false;

    self.startTimes = [];

    self.sec2humanReadable = function (duration) {
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

    self.setStartTimes = function() {
        self.startTimes = [];

        var startAt = moment().subtract(80, 'seconds').diff(
            moment('2015-06-06T13:00:00+03:00'),
            'seconds'
        );

        startAt = startAt - startAt%20;

        for (var i = 0; i < 20; i++) {
            self.startTimes.push({
                strTime: self.sec2humanReadable(startAt),
                time: startAt,
                number: 0,
            });
            startAt = startAt + 20;
        }
    };

    self.updateStartTimes = function() {
        var startAt = moment().diff(
            moment('2015-06-06T13:00:00+03:00'),
            'seconds'
        );

        for (var i = 0; i < self.startTimes.length; i++) {
            if ((startAt - self.startTimes[i].time) > 80) {
                var x = self.startTimes.splice(i, 1);
                self.startTimes.push({
                    strTime: self.sec2humanReadable(self.startTimes[self.startTimes.length-1].time+20),
                    time: self.startTimes[self.startTimes.length-1].time+20,
                });
            }
        }
    }

    $interval(self.updateStartTimes, 1000);


    self.setStartTimes();
}]);
