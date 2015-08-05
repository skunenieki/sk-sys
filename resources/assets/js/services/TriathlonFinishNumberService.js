angular.module('skApp.TriathlonFinishNumberService', [])
.factory('TriathlonFinishNumberService', ['$resource', function($resource) {
    return $resource(
        '/triathlon/finishnumbers/:id',
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
