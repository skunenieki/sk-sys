angular.module('skApp.MainController', [])
    .controller('MainController', ['OptionStateService', 'PersistentStateService', function(OptionStateService, PersistentStateService) {
        var self = this;

        self.options = OptionStateService;
        OptionStateService.refreshOptions();

        self.timer = PersistentStateService.timer;
    }]);
