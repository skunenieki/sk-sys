angular.module('skApp.MainController', [])
    .controller('MainController', ['OptionStateService', function(OptionStateService) {
        var self = this;

        self.options = OptionStateService;
        OptionStateService.refreshOptions();
    }]);
