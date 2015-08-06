angular.module('skApp.PersistentStateService', [])
.service('PersistentStateService', ['$rootScope', '$route', '$interval', 'AppConfig', function($rootScope, $routeSegment, $interval, AppConfig) {
    var self = this;

    var moment = require('moment');

    self.turnInterval            = false;
    self.startInterval           = false;
    self.potentialFinishersTimer = false;

    self.bestSummaryTimer        = false;

    self.chronoInterval          = false;

    self.chronometer = null;

    self.timer = function() {
        return self.chronometer;
    };

    self.updateChronometer = function() {
        if (null !== self.activeEventType) {
            self.chronometer = self.sec2humanReadable(
                moment().diff(
                    moment(AppConfig[self.activeEventType+'ActiveEventStart']),
                    'seconds'
                )
            );
        } else {
            self.chronometer = null;
        }
    };

    $rootScope.$on('$routeChangeSuccess', function() {
        switch ($routeSegment.current.segment.split('.')[0]) {
            case '10km':
                self.activeEventType = 'ind';
                break;
            case 'mtb':
                self.activeEventType = 'mtb';
                break;
            case 'triathlon':
                self.activeEventType = 'triathlon';
                break;
            case 'football':
                self.activeEventType = 'football';
                break;
            default:
                self.activeEventType = null;
        }
    });

    self.sec2humanReadable = function (duration) {
        var hour = 0;
        var min = 0;
        var sec = 0;

        var negDuration = '';
        if (duration < 0) {
            negDuration = '-';
            duration = (duration * -1) + 1;
        }

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

        return negDuration+hour +":"+ min +":"+ sec;
    }

    if (false === self.chronoInterval) {
        self.chronoInterval = $interval(self.updateChronometer, 100);
    }
}]);
