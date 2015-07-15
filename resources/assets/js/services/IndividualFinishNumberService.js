angular.module('skApp.IndividualFinishNumberService', [])
.factory('IndividualFinishNumberService', ['$resource', function($resource) {
    return $resource(
        '/10km/finishnumbers/:id',
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
