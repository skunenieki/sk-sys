angular.module('skApp.TriathlonFinishTimeService', [])
.factory('TriathlonFinishTimeService', ['$resource', function($resource) {
    return $resource(
        '/triathlon/finishtimes/:id',
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
