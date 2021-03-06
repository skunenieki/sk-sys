angular.module('skApp.TriathlonService', [])
.factory('TriathlonService', ['$resource', function($resource) {
    return $resource(
        '/triathlon/individual/:id',
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
