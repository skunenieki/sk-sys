angular.module('skApp.TriathlonTeamService', [])
.factory('TriathlonTeamService', ['$resource', function($resource) {
    return $resource(
        '/triathlon/teams/:id',
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
