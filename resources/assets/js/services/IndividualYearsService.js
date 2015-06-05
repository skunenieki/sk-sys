angular.module('skApp.IndividualYearsService', [])
.factory('IndividualYearsService', ['$resource', function($resource) {
    return $resource(
        '/10km/years',
    );
}])
