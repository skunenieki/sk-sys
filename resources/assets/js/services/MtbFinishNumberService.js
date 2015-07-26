angular.module('skApp.MtbFinishNumberService', [])
.factory('MtbFinishNumberService', ['$resource', function($resource) {
    return $resource(
        '/mtb/finishnumbers/:id',
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
