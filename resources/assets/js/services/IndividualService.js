angular.module('skApp.IndividualService', [])
.factory('IndividualService', ['$resource', function($resource) {
    return $resource(
        '/10km/:id',
        {
            id:'@id',
        },
        {
            get: {
                transformResponse: function(data, headers) {
                    var moment = require('moment');
                    data = angular.fromJson(data);
                    data.birthYear = moment(data.birthYear).year();
                    data.eventYear = moment(data.eventYear).year();
                    return data;
                }
            },
            update: {method: 'PUT'},
        },
        {
            stripTrailingSlashes: true,
        }
    );
}])
