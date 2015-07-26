angular.module('skApp.MtbFinishTimeService', [])
.factory('MtbFinishTimeService', ['$resource', function($resource) {
    return $resource(
        '/mtb/finishtimes/:id',
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
