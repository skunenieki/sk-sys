angular.module('skApp.ParticipantService', [])
.factory('ParticipantService', ['$resource', function($resource) {
    return $resource(
        '/participants/:id',
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
