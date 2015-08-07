angular.module('skApp.KidsService', [])
.factory('KidsService', ['$resource', function($resource) {
    return $resource(
        '/kids/:id',
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
