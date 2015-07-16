angular.module('skApp.EventService', [])
.factory('EventService', ['$resource', function($resource) {
    return $resource(
        '/events/:eventYear',
        {},
        {
            get: {
                params: {
                    eventYear:'@eventYear',
                },
            },
            update: {
                method: 'PUT',
                params: {
                    eventYear:'@eventYear',
                },
            },
        },
        {
            stripTrailingSlashes: true,
        }
    );
}])
