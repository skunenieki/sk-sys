angular.module('skApp.IndividualTurnService', [])
.factory('IndividualTurnService', ['$resource', function($resource) {
    return $resource(
        '/10km/turns',
        {
            id:'@id',
        },
        {
            update: {method: 'PUT'},
        },
        {
            stripTrailingSlashes: true,
        }
    );
}])
