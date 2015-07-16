angular.module('skApp.OptionService', [])
.factory('OptionService', ['$resource', function($resource) {
    return $resource(
        '/options/:id',
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
