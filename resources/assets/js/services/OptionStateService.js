angular.module('skApp.OptionStateService', [])
.service('OptionStateService', ['OptionService', function(OptionService) {
    var self = this;
    var _ = require('underscore');

    self.options = null;

    self.refreshOptions = function(force) {
        if (self.options === null || force === true) {
            self.options = {};
            OptionService.query({}, function(response) {
                _.each(response, function(value) {
                    self.options[value.key] = value.value;
                });
            });
        }
    };

    self.get = function(optionName) {
        return self.options[optionName];
    };

    self.refreshOptions();
}]);
