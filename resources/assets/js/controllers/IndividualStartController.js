angular.module('skApp.IndividualStartController', [])
.controller('IndividualStartController', ['$interval', 'IndividualService', function($interval, IndividualService) {
    var self = this;
    var moment = require('moment');

    self.individualStartTime = '2015-06-18T20:00:00+03:00';

    self.currentTime = false;

    self.startTimes = [];

    self.status = '';

    self.saveStart = function(idx) {
        if (typeof self.startTimes[idx].bikeType !== 'undefined' && typeof self.startTimes[idx].number !== 'undefined' && self.startTimes[idx].number !== null) {
            if (self.startTimes[idx].status !== 'confirmed') {
                self.startTimes[idx].status = 'saving';
            }

            IndividualService.query({
                eventYear: 2015,
                number: self.startTimes[idx].number,
                limit: 1
            }, function(response) {
                if (response.length > 0) {
                    if (response[0].start === null || self.startTimes[idx].status === 'confirmed') {
                        response[0].start    = self.startTimes[idx].strTime;
                        response[0].bikeType = self.startTimes[idx].bikeType;

                        self.startTimes[idx].name = response[0].name;

                        IndividualService.update(response[0], function(response) {
                            for (var i = 0; i < self.startTimes.length; i++) {
                                if (self.startTimes[i].number === response.number && self.startTimes[i].strTime !== response.start) {
                                    self.startTimes[i].number   = '';
                                    self.startTimes[i].name     = '';
                                    self.startTimes[i].bikeType = '';
                                    self.startTimes[i].group    = '';
                                    self.startTimes[i].status   = '';
                                }
                            }

                            self.startTimes[idx].status = 'saved';
                            self.startTimes[idx].group = response.group;
                            self.status = '';
                        });
                    } else {
                        self.startTimes[idx].status = 'unconfirmed';
                        self.status = 'invalid';
                    }
                } else {
                    self.startTimes[idx].status = 'failed';
                    self.status = 'invalid';
                }
            });
        }
    };

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
            moment(self.individualStartTime),
            'seconds'
        );

        startAt = startAt - startAt%20;

        var humanTime = '';
        var timeQuery = '';
        for (var i = 0; i < 20; i++) {
            humanTime = self.sec2humanReadable(startAt);
            timeQuery = timeQuery + (timeQuery === '' ? '' : ',') + humanTime;
            self.startTimes.push({
                strTime: humanTime,
                time: startAt,
            });
            startAt = startAt + 20;
        }

        IndividualService.query({
            eventYear: 2015,
            start: timeQuery,
        }, function(response) {
            for (var i = 0; i < self.startTimes.length; i++) {
                for (var j = 0; j < response.length; j++) {
                    if (self.startTimes[i].strTime === response[j].start) {
                        self.startTimes[i].number   = response[j].number;
                        self.startTimes[i].bikeType = response[j].bikeType;
                        self.startTimes[i].name     = response[j].name;
                        self.startTimes[i].group    = response[j].group;
                        self.startTimes[i].status   = 'saved';
                        response.splice(j, 1);
                    }
                }
            }
        });
    };

    self.updateStartTimes = function() {
        if (self.status !== 'invalid') {
            var startAt = moment().diff(
                moment(self.individualStartTime),
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
    }

    $interval(self.updateStartTimes, 1000);


    self.setStartTimes();
}]);
