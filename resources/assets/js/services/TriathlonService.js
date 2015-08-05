angular.module('skApp.TriathlonService', [])
.factory('TriathlonService', ['$resource', function($resource) {
    return $resource(
        '/mtb/:id',
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
