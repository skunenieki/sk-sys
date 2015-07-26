angular.module('skApp.MtbService', [])
.factory('MtbService', ['$resource', function($resource) {
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
