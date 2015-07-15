angular.module('skApp.IndividualFinishTimeService', [])
.factory('IndividualFinishTimeService', ['$resource', function($resource) {
    return $resource(
        '/10km/finishtimes/:id',
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
