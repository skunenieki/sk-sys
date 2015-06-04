angular.module('skApp.IndividualService', [])
.factory('IndividualService', ['$resource', function($resource) {
    return $resource(
        '/10km/:id',
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
